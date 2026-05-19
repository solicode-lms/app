<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgGapp\Models\ERelationship;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe ERelationshipService pour gérer la persistance de l'entité ERelationship.
 */
class BaseERelationshipService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour eRelationships.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'reference',
        'name',
        'type',
        'source_e_model_id',
        'target_e_model_id',
        'cascade_on_delete',
        'is_cascade',
        'description',
        'column_name',
        'referenced_table',
        'referenced_column',
        'through',
        'with_column',
        'morph_name'
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
     * Constructeur de la classe ERelationshipService.
     */
    public function __construct()
    {
        parent::__construct(new ERelationship());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGapp::eRelationship.plural');
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
            $eRelationship = $this->find($data['id']);
            $eRelationship->fill($data);
        } else {
            $eRelationship = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($eRelationship->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $eRelationship->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($eRelationship->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($eRelationship->id, $data);
            }
        }

        return $eRelationship;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('eRelationship');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('type', $scopeVariables)) {
                    $this->fieldsFilterable[] = [
                        'field' => 'type', 
                        'type'  => 'String', 
                        'label' => 'type'
                    ];
                }
            
            
                if (!array_key_exists('source_e_model_id', $scopeVariables)) {


                    $eModelService = new \Modules\PkgGapp\Services\EModelService();
                    $eModelIds = $this->getAvailableFilterValues('source_e_model_id');
                    $eModels = $eModelService->getByIds($eModelIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgGapp::eModel.plural"), 
                        'source_e_model_id', 
                        \Modules\PkgGapp\Models\EModel::class, 
                        'name',
                        $eModels
                    );
                }
            
            
                if (!array_key_exists('target_e_model_id', $scopeVariables)) {


                    $eModelService = new \Modules\PkgGapp\Services\EModelService();
                    $eModelIds = $this->getAvailableFilterValues('target_e_model_id');
                    $eModels = $eModelService->getByIds($eModelIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgGapp::eModel.plural"), 
                        'target_e_model_id', 
                        \Modules\PkgGapp\Models\EModel::class, 
                        'name',
                        $eModels
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de eRelationship.
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
    public function getERelationshipStats(): array
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
            'table' => 'PkgGapp::eRelationship._table',
            default => 'PkgGapp::eRelationship._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('eRelationship_view_type', $default_view_type);
        $eRelationship_viewType = $this->viewState->get('eRelationship_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('eRelationship_view_type') === 'widgets') {
            $this->viewState->set("scope.eRelationship.visible", 1);
        }else{
            $this->viewState->remove("scope.eRelationship.visible");
        }
        
        // Récupération des données
        $eRelationships_data = $this->paginate($params);
        $eRelationships_stats = $this->geteRelationshipStats();
        $eRelationships_total = $this->count();
        $eRelationships_filters = $this->getFieldsFilterable();
        $eRelationship_instance = $this->createInstance();
        $eRelationship_viewTypes = $this->getViewTypes();
        $eRelationship_partialViewName = $this->getPartialViewName($eRelationship_viewType);
        $eRelationship_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.eRelationship.stats', $eRelationships_stats);
    
        $eRelationships_permissions = [

            'edit-eRelationship' => Auth::user()->can('edit-eRelationship'),
            'destroy-eRelationship' => Auth::user()->can('destroy-eRelationship'),
            'show-eRelationship' => Auth::user()->can('show-eRelationship'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $eRelationships_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($eRelationships_data as $item) {
                $eRelationships_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'eRelationship_viewTypes',
            'eRelationship_viewType',
            'eRelationships_data',
            'eRelationships_stats',
            'eRelationships_total',
            'eRelationships_filters',
            'eRelationship_instance',
            'eRelationship_title',
            'contextKey',
            'eRelationships_permissions',
            'eRelationships_permissionsByItem'
        );
    
        return [
            'eRelationships_data' => $eRelationships_data,
            'eRelationships_stats' => $eRelationships_stats,
            'eRelationships_total' => $eRelationships_total,
            'eRelationships_filters' => $eRelationships_filters,
            'eRelationship_instance' => $eRelationship_instance,
            'eRelationship_viewType' => $eRelationship_viewType,
            'eRelationship_viewTypes' => $eRelationship_viewTypes,
            'eRelationship_partialViewName' => $eRelationship_partialViewName,
            'contextKey' => $contextKey,
            'eRelationship_compact_value' => $compact_value,
            'eRelationships_permissions' => $eRelationships_permissions,
            'eRelationships_permissionsByItem' => $eRelationships_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $eRelationship_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $eRelationship_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($eRelationship_ids as $id) {
            $eRelationship = $this->find($id);
            $this->authorize('update', $eRelationship);
    
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
            'name',
            'type',
            'source_e_model_id',
            'target_e_model_id'
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
    public function buildFieldMeta(ERelationship $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgGapp\App\Requests\ERelationshipRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'e_relationship',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'name':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'type':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'source_e_model_id':
                 $values = (new \Modules\PkgGapp\Services\EModelService())
                    ->getAllForSelect($e->sourceEModel)
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
            case 'target_e_model_id':
                 $values = (new \Modules\PkgGapp\Services\EModelService())
                    ->getAllForSelect($e->targetEModel)
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
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(ERelationship $e, array $changes): ERelationship
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
    public function formatDisplayValues(ERelationship $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'name':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'type':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'source_e_model_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'sourceEModel'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'target_e_model_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'targetEModel'
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
