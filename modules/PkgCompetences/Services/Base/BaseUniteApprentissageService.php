<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe UniteApprentissageService pour gérer la persistance de l'entité UniteApprentissage.
 */
class BaseUniteApprentissageService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour uniteApprentissages.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'code',
        'nom',
        'micro_competence_id',
        'lien',
        'description'
    ];

    /**
     * Renvoie les champs de recherche disponibles.
     *
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldsSearchable;
    }

    /**
     * Constructeur de la classe UniteApprentissageService.
     */
    public function __construct()
    {
        parent::__construct(new UniteApprentissage());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::uniteApprentissage.plural');
    }


    /**
     * Applique les calculs dynamiques sur les champs marqués avec l’attribut `data-calcule`
     * pendant l’édition ou la création d’une entité.
     *
     * Cette méthode est utilisée dans les formulaires dynamiques pour recalculer certains champs
     * (ex : note, barème, état, progression...) en fonction des valeurs saisies ou modifiées.
     *
     * Elle est déclenchée automatiquement lorsqu’un champ du formulaire possède l’attribut `data-calcule`.
     *
     * @param mixed $data Données en cours d’édition (array ou modèle hydraté sans persistance).
     * @return mixed L’entité enrichie avec les champs recalculés.
     */
    public function dataCalcul($data)
    {
        // 🧾 Chargement ou initialisation de l'entité
        if (!empty($data['id'])) {
            $uniteApprentissage = $this->find($data['id']);
            $uniteApprentissage->fill($data);
        } else {
            $uniteApprentissage = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($uniteApprentissage->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $uniteApprentissage->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($uniteApprentissage->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($uniteApprentissage->id, $data);
            }
        }

        return $uniteApprentissage;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('uniteApprentissage');
        $this->fieldsFilterable = [];
        
            
                $filiereService = new \Modules\PkgFormation\Services\FiliereService();
                $filiereIds = $this->getAvailableFilterValues('MicroCompetence.Competence.Module.filiere_id');
                $filieres = $filiereService->getByIds($filiereIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgFormation::filiere.plural"),
                    'MicroCompetence.Competence.Module.filiere_id', 
                    \Modules\PkgFormation\Models\Filiere::class,
                    "id", 
                    "id",
                    $filieres,
                    "[name='micro_competence_id']",
                    route('microCompetences.getData'),
                    "competence.module.filiere_id"
                    
                );
            
            
                if (!array_key_exists('micro_competence_id', $scopeVariables)) {


                    $microCompetenceService = new \Modules\PkgCompetences\Services\MicroCompetenceService();
                    $microCompetenceIds = $this->getAvailableFilterValues('micro_competence_id');
                    $microCompetences = $microCompetenceService->getByIds($microCompetenceIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::microCompetence.plural"), 
                        'micro_competence_id', 
                        \Modules\PkgCompetences\Models\MicroCompetence::class, 
                        'titre',
                        $microCompetences
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de uniteApprentissage.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array|object $data)
    {
        return parent::create($data);
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getUniteApprentissageStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }




    /**
     * Retourne les types de vues disponibles pour l'index (ex: table, widgets...)
     */
    public function getViewTypes(): array
    {
        return [
            [
                'type'  => 'table',
                'label' => 'Vue Tableau',
                'icon'  => 'fa-table',
            ],
        ];
    }

    /**
     * Retourne le nom de la vue partielle selon le type de vue sélectionné
     */
    public function getPartialViewName(string $viewType): string
    {
        return match ($viewType) {
            'table' => 'PkgCompetences::uniteApprentissage._table',
            default => 'PkgCompetences::uniteApprentissage._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('uniteApprentissage_view_type', $default_view_type);
        $uniteApprentissage_viewType = $this->viewState->get('uniteApprentissage_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('uniteApprentissage_view_type') === 'widgets') {
            $this->viewState->set("scope.uniteApprentissage.visible", 1);
        }else{
            $this->viewState->remove("scope.uniteApprentissage.visible");
        }
        
        // Récupération des données
        $uniteApprentissages_data = $this->paginate($params);
        $uniteApprentissages_stats = $this->getuniteApprentissageStats();
        $uniteApprentissages_total = $this->count();
        $uniteApprentissages_filters = $this->getFieldsFilterable();
        $uniteApprentissage_instance = $this->createInstance();
        $uniteApprentissage_viewTypes = $this->getViewTypes();
        $uniteApprentissage_partialViewName = $this->getPartialViewName($uniteApprentissage_viewType);
        $uniteApprentissage_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.uniteApprentissage.stats', $uniteApprentissages_stats);
    
        $uniteApprentissages_permissions = [

            'edit-uniteApprentissage' => Auth::user()->can('edit-uniteApprentissage'),
            'destroy-uniteApprentissage' => Auth::user()->can('destroy-uniteApprentissage'),
            'show-uniteApprentissage' => Auth::user()->can('show-uniteApprentissage'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $uniteApprentissages_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($uniteApprentissages_data as $item) {
                $uniteApprentissages_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'uniteApprentissage_viewTypes',
            'uniteApprentissage_viewType',
            'uniteApprentissages_data',
            'uniteApprentissages_stats',
            'uniteApprentissages_total',
            'uniteApprentissages_filters',
            'uniteApprentissage_instance',
            'uniteApprentissage_title',
            'contextKey',
            'uniteApprentissages_permissions',
            'uniteApprentissages_permissionsByItem'
        );
    
        return [
            'uniteApprentissages_data' => $uniteApprentissages_data,
            'uniteApprentissages_stats' => $uniteApprentissages_stats,
            'uniteApprentissages_total' => $uniteApprentissages_total,
            'uniteApprentissages_filters' => $uniteApprentissages_filters,
            'uniteApprentissage_instance' => $uniteApprentissage_instance,
            'uniteApprentissage_viewType' => $uniteApprentissage_viewType,
            'uniteApprentissage_viewTypes' => $uniteApprentissage_viewTypes,
            'uniteApprentissage_partialViewName' => $uniteApprentissage_partialViewName,
            'contextKey' => $contextKey,
            'uniteApprentissage_compact_value' => $compact_value,
            'uniteApprentissages_permissions' => $uniteApprentissages_permissions,
            'uniteApprentissages_permissionsByItem' => $uniteApprentissages_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $uniteApprentissage_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $uniteApprentissage_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($uniteApprentissage_ids as $id) {
            $uniteApprentissage = $this->find($id);
            $this->authorize('update', $uniteApprentissage);
    
            $allFields = $this->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $valeursChamps[$field]])
                ->toArray();
    
            if (!empty($data)) {
                $this->updateOnlyExistanteAttribute($id, $data);
            }

            $jobManager->tick();
            
        }

        return "done";
    }

    /**
    * Liste des champs autorisés à l’édition inline
    */
    public function getFieldsEditable(): array
    {
        return [
            'ordre',
            'nom',
            'micro_competence_id',
            'lien',
            'Chapitre'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(UniteApprentissage $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCompetences\App\Requests\UniteApprentissageRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'unite_apprentissage',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'ordre':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            case 'nom':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'micro_competence_id':
                 $values = (new \Modules\PkgCompetences\Services\MicroCompetenceService())
                    ->getAllForSelect($e->microCompetence)
                    ->map(fn($entity) => [
                        'value' => (int) $entity->id,
                        'label' => (string) $entity,
                    ])
                    ->toArray();

                return $this->computeFieldMeta($e, $field, $meta, 'select', [
                    'required' => true,
                    'options'  => [
                        'source' => 'static',
                        'values' => $values,
                    ],
                ]);
            case 'lien':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'Chapitre':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(UniteApprentissage $e, array $changes): UniteApprentissage
    {
        $allowed = $this->getFieldsEditable();
        $filtered = Arr::only($changes, $allowed);

        if (empty($filtered)) {
            abort(422, 'Aucun champ autorisé.');
        }

        $rules = [];
        foreach ($filtered as $field => $value) {
            $meta = $this->buildFieldMeta($e, $field);
            $rules[$field] = $meta['validation'] ?? ['nullable'];
        }
        
        $e->fill($filtered);
        Validator::make($e->toArray(), $rules)->validate();
        $e = $this->updateOnlyExistanteAttribute($e->id, $filtered);

        return $e;
    }

    /**
     * Formatte les valeurs pour l’affichage inline
     */
    public function formatDisplayValues(UniteApprentissage $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'ordre':
                    $html = view('Core::fields_by_type.integer', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'ordre'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'nom':
                    // Vue custom définie pour ce champ
                    $html = view('PkgCompetences::uniteApprentissage.custom.fields.nom', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'micro_competence_id':
                    // Vue custom définie pour ce champ
                    $html = view('PkgCompetences::uniteApprentissage.custom.fields.microCompetence', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'lien':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'lien'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'Chapitre':
                    // fallback string simple
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;

                default:
                    // fallback générique si champ non pris en charge
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                    ])->render();

                    $out[$field] = ['html' => $html];
            }
        }
        return $out;
    }
}
