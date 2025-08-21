<?php

namespace Modules\Core\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Manager\JobManager;
use Modules\Core\Services\Contracts\ServiceInterface;

use Modules\Core\Services\Traits\{
    CrudCreateTrait,
    CrudDeleteTrait,
    CrudEditTrait,
    CrudReadTrait,
    MessageTrait,
    PaginateTrait,
    QueryBuilderTrait,
    CrudTrait,
    CrudUpdateTrait,
    RelationTrait,
    FilterTrait,
    SortTrait,
    StatsTrait,
    OrdreTraite,
    HandleThrowableTrait,
    JobTrait
};
use Modules\PkgRealisationTache\Models\RealisationTache;

/**
 * Classe abstraite BaseService qui fournit une implémentation de base
 * pour les opérations courantes de manipulation des données.
 */
abstract class BaseService implements ServiceInterface
{

    use 
        MessageTrait,
        QueryBuilderTrait, 
        PaginateTrait, 
        SortTrait,
        OrdreTraite,
        CrudTrait, 
        CrudReadTrait, 
        CrudCreateTrait, 
        CrudUpdateTrait, 
        CrudDeleteTrait, 
        CrudEditTrait, 
        RelationTrait, 
        FilterTrait, 
        StatsTrait,
        HandleThrowableTrait,
        JobTrait;


    // protected ?string $crudJobToken = null;
    public function getCrudJobToken(): ?string
    {
        $v =  app()->bound('current_crud_job_token')
            ? app('current_crud_job_token')
            : null;
        return $v;
    }

    public function setCrudJobToken($crudJobToken): void
    {
        // Stocker le token uniquement pour la durée de la requête
        app()->instance('current_crud_job_token', $crudJobToken);
    }


    // EagerLoading Charger les relations nécessaires : il est utilisé dans PaginateTrait
    protected array $index_with_relations = [];


    protected array $fieldsFilterable;

    protected  $fieldsSearchable;

    protected $viewState;
    protected $sessionState;
    protected $model;
    public $modelName;

    public $moduleName;
    protected $paginationLimit = 20;

    
    protected $totalFilteredCount;

    public $userHasSentFilter = false;

    /**
     * Le titre à afficher dnas la page Index
     *
     * @var [type]
     */
    protected $title;

    /** Configuration pour afficher CRUD sur une source donnée Query à partie d'une méthode 
     * qui retourne un objet de type builder
     */
    protected $dataSources = [];

    /**
     * Colonne utilisée pour grouper les enregistrements lors du tri par ordre.
     * Exemple : 'projet_id' pour les tâches.
     */
    protected $ordreGroupColumn = null;


    /**
     * Méthode abstraite pour obtenir les champs recherchables.
     *
     * @return array
     */
    abstract public function getFieldsSearchable(): array;


    public function getFieldsEditable(): array
    {
        return $this->fieldsSearchable;
    }
    
    /**
     * Méthode pour obtenir les champs sortable.
     * Les champs dynamique sont sortable mais ne sont pas searchable
     *
     * @return array
     */
    public function getFieldsSortable(): array
    {
        $dynamicAttributes = array_keys($this->model->getDynamicAttributes());
        
        // On fusionne les champs "searchable" (en base) et les attributs dynamiques (calculés)
        return array_merge($this->fieldsSearchable, $dynamicAttributes, ['created_at', 'updated_at']);
    }

    
    /**
     * Constructeur de la classe BaseService.
     *
     * @param Model $model Le modèle Eloquent associé au référentiel.
     */
    public function __construct(Model $model){
        $this->model = $model;
        $this->modelName = lcfirst(class_basename($model));
        
        
        // 📌 Déterminer automatiquement le nom du module à partir du namespace du modèle
        $fullNamespace = get_class($model); // ex: Modules\PkgRealisationProjets\Models\AffectationProjet
        $parts = explode('\\', $fullNamespace);
        $this->moduleName = $parts[1] ?? null; // ex: "PkgRealisationProjets"

        // Scrop management
        $this->viewState = app(ViewStateService::class);
        $this->sessionState = app(SessionState::class);
    }

    public function getData(string $filter, $value)
    {
        //  TODO : $query = $this->newQuery();
        $query = $this->allQuery(); // Créer une nouvelle requête

        // Construire le tableau de filtres pour la méthode `filter()`
        $filters = [$filter => $value];

        // Appliquer le filtre existant du service
        $this->filter($query, $this->model, $filters);

        return $query->get();
    }


    /**
         * Résout dynamiquement le nom de la classe à partir de son nom court (ex: "Apprenant"),
         * en cherchant dans les namespaces des modules déclarés dans SoliLMS.
         *
         * @param string $className Nom court de la classe (ex: "Apprenant")
         * @return object|null Instance de la classe si trouvée, sinon null
         */
    function resolveClassByName(string $className): ?object
    {
        $modulePaths = [
            'PkgApprenants',
            'PkgFormation',
            'PkgCompetences',
            'PkgCreationProjet',
            'PkgRealisationProjets',
            'PkgCreationTache',
            'PkgRealisationTache',
            'PkgApprentissage',
            'PkgEvaluateurs',
            'PkgNotification',
            'PkgAutorisation',
            'PkgWidgets',
            'PkgSessions',
            'PkgGapp',
            'Core'
        ];

        foreach ($modulePaths as $module) {
            $fqcn = "Modules\\$module\\Services\\$className";
            if (class_exists($fqcn)) {
                return new $fqcn();
            }

            // En fallback, certains modules utilisent Entities à la place de Models
            $fqcnEntity = "Modules\\$module\\Models\\$className";
            if (class_exists($fqcnEntity)) {
                return new $fqcnEntity();
            }
        }

        return null;
    }

    /**
     * Vérifie si l'utilisateur courant est autorisé à exécuter une action sur une entité.
     *
     * @param string $ability  Nom de l'action (ex: 'view', 'update', 'delete')
     * @param mixed  $entity   L'entité ou la classe concernée
     *
     * @throws AuthorizationException
     */
    protected function authorize(string $ability, mixed $entity): void
    {
        if (Gate::denies($ability, $entity)) {
            throw new AuthorizationException("Vous n'êtes pas autorisé à effectuer cette action." . $ability . "in" . $entity );
        }
        
    }

    // Inline Edit

    /**
     * Génère un ETag basé sur updated_at
     */
    public function etag($entity): string
    {
        $ver = optional($entity->updated_at)->timestamp ?? 0;
        return  $this->modelName . '-' . $entity->id . '-' . $ver . '"';
    }

    /**
     * Méthode générique qui calcule la meta en fonction du type et des paramètres.
     *
     * @param  RealisationTache $e
     * @param  string           $field
     * @param  array            $baseMeta
     * @param  string           $type
     * @param  array            $validationRules
     * @param  array            $extra
     * @return array
     */
    protected function computeFieldMeta(RealisationTache $e, string $field, array $baseMeta, string $type, array $validationRules, array $extra = []): array
    {
        // 🔹 Calcul automatique de la valeur en fonction du type
        $value = match ($type) {
            'date'    => optional($e->$field)->format('Y-m-d'),
            'boolean' => (bool) $e->$field,
            default   => $e->$field,
        };

        return array_merge($baseMeta, [
            'type'       => $type,
            'validation' => $validationRules,
            'value'      => $value,
        ], $extra);
    }


}