<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprenants\Models\Groupe;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe GroupeService pour gérer la persistance de l'entité Groupe.
 */
class BaseGroupeService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour groupes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'nom',
        'description',
        'filiere_id',
        'annee_formation_id',
        'reference'
    ];



    public function editableFieldsByRoles(): array
    {
        return [
        
        ];
    }


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
     * Constructeur de la classe GroupeService.
     */
    public function __construct()
    {
        parent::__construct(new Groupe());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprenants::groupe.plural');
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
            $groupe = $this->find($data['id']);
            $groupe->fill($data);
        } else {
            $groupe = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($groupe->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $groupe->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($groupe->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($groupe->id, $data);
            }
        }

        return $groupe;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('groupe');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('filiere_id', $scopeVariables)) {


                    $filiereService = new \Modules\PkgFormation\Services\FiliereService();
                    $filiereIds = $this->getAvailableFilterValues('filiere_id');
                    $filieres = $filiereService->getByIds($filiereIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::filiere.plural"), 
                        'filiere_id', 
                        \Modules\PkgFormation\Models\Filiere::class, 
                        'code',
                        $filieres
                    );
                }
            
            
                if (!array_key_exists('annee_formation_id', $scopeVariables)) {


                    $anneeFormationService = new \Modules\PkgFormation\Services\AnneeFormationService();
                    $anneeFormationIds = $this->getAvailableFilterValues('annee_formation_id');
                    $anneeFormations = $anneeFormationService->getByIds($anneeFormationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::anneeFormation.plural"), 
                        'annee_formation_id', 
                        \Modules\PkgFormation\Models\AnneeFormation::class, 
                        'titre',
                        $anneeFormations
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de groupe.
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
    public function getGroupeStats(): array
    {

        $stats = $this->initStats();

        
            $relationStatFiliere = parent::getStatsByRelation(
                \Modules\PkgFormation\Models\Filiere::class,
                'groupes',
                'code'
            );
            $stats = array_merge($stats, $relationStatFiliere);

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
            'table' => 'PkgApprenants::groupe._table',
            default => 'PkgApprenants::groupe._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('groupe_view_type', $default_view_type);
        $groupe_viewType = $this->viewState->get('groupe_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('groupe_view_type') === 'widgets') {
            $this->viewState->set("scope.groupe.visible", 1);
        }else{
            $this->viewState->remove("scope.groupe.visible");
        }
        
        // Récupération des données
        $groupes_data = $this->paginate($params);
        $groupes_stats = $this->getgroupeStats();
        $groupes_total = $this->count();
        $groupes_filters = $this->getFieldsFilterable();
        $groupe_instance = $this->createInstance();
        $groupe_viewTypes = $this->getViewTypes();
        $groupe_partialViewName = $this->getPartialViewName($groupe_viewType);
        $groupe_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.groupe.stats', $groupes_stats);
    
        $groupes_permissions = [

            'edit-groupe' => Auth::user()->can('edit-groupe'),
            'destroy-groupe' => Auth::user()->can('destroy-groupe'),
            'show-groupe' => Auth::user()->can('show-groupe'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $groupes_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($groupes_data as $item) {
                $groupes_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'groupe_viewTypes',
            'groupe_viewType',
            'groupes_data',
            'groupes_stats',
            'groupes_total',
            'groupes_filters',
            'groupe_instance',
            'groupe_title',
            'contextKey',
            'groupes_permissions',
            'groupes_permissionsByItem'
        );
    
        return [
            'groupes_data' => $groupes_data,
            'groupes_stats' => $groupes_stats,
            'groupes_total' => $groupes_total,
            'groupes_filters' => $groupes_filters,
            'groupe_instance' => $groupe_instance,
            'groupe_viewType' => $groupe_viewType,
            'groupe_viewTypes' => $groupe_viewTypes,
            'groupe_partialViewName' => $groupe_partialViewName,
            'contextKey' => $contextKey,
            'groupe_compact_value' => $compact_value,
            'groupes_permissions' => $groupes_permissions,
            'groupes_permissionsByItem' => $groupes_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $groupe_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $groupe_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($groupe_ids as $id) {
            $groupe = $this->find($id);
            $this->authorize('update', $groupe);
    
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
    public function getInlineFieldsEditable(): array
    {
        // Champs considérés comme inline
        $inlineFields = [
            'code',
            'filiere_id',
            'formateurs'
        ];

        // Récupération des champs autorisés par rôle via getFieldsEditable()
        return array_values(array_intersect(
            $inlineFields,
            $this->getFieldsEditable()
        ));
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(Groupe $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgApprenants\App\Requests\GroupeRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'groupe',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'code':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'filiere_id':
                 $values = (new \Modules\PkgFormation\Services\FiliereService())
                    ->getAllForSelect($e->filiere)
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
            case 'formateurs':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(Groupe $e, array $changes): Groupe
    {
        $allowed = $this->getInlineFieldsEditable();
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
    public function formatDisplayValues(Groupe $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'code':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'filiere_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'filiere'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'formateurs':
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
