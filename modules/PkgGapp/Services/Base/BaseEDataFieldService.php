<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgGapp\Models\EDataField;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe EDataFieldService pour gérer la persistance de l'entité EDataField.
 */
class BaseEDataFieldService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour eDataFields.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'e_model_id',
        'data_type',
        'default_value',
        'column_name',
        'e_relationship_id',
        'field_order',
        'db_primaryKey',
        'db_nullable',
        'db_unique',
        'calculable',
        'calculable_sql',
        'description'
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
     * Constructeur de la classe EDataFieldService.
     */
    public function __construct()
    {
        parent::__construct(new EDataField());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGapp::eDataField.plural');
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
            $eDataField = $this->find($data['id']);
            $eDataField->fill($data);
        } else {
            $eDataField = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($eDataField->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $eDataField->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($eDataField->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($eDataField->id, $data);
            }
        }

        return $eDataField;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('eDataField');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('e_model_id', $scopeVariables)) {


                    $eModelService = new \Modules\PkgGapp\Services\EModelService();
                    $eModelIds = $this->getAvailableFilterValues('e_model_id');
                    $eModels = $eModelService->getByIds($eModelIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgGapp::eModel.plural"), 
                        'e_model_id', 
                        \Modules\PkgGapp\Models\EModel::class, 
                        'name',
                        $eModels
                    );
                }
            
            
                if (!array_key_exists('data_type', $scopeVariables)) {
                    $this->fieldsFilterable[] = [
                        'field' => 'data_type', 
                        'type'  => 'String', 
                        'label' => 'data_type'
                    ];
                }
            
            
                if (!array_key_exists('calculable', $scopeVariables)) {
                    $this->fieldsFilterable[] = [
                        'field' => 'calculable', 
                        'type'  => 'Boolean', 
                        'label' => 'calculable'
                    ];
                }
            



    }


    /**
     * Crée une nouvelle instance de eDataField.
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
    public function getEDataFieldStats(): array
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
            'table' => 'PkgGapp::eDataField._table',
            default => 'PkgGapp::eDataField._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('eDataField_view_type', $default_view_type);
        $eDataField_viewType = $this->viewState->get('eDataField_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('eDataField_view_type') === 'widgets') {
            $this->viewState->set("scope.eDataField.visible", 1);
        }else{
            $this->viewState->remove("scope.eDataField.visible");
        }
        
        // Récupération des données
        $eDataFields_data = $this->paginate($params);
        $eDataFields_stats = $this->geteDataFieldStats();
        $eDataFields_total = $this->count();
        $eDataFields_filters = $this->getFieldsFilterable();
        $eDataField_instance = $this->createInstance();
        $eDataField_viewTypes = $this->getViewTypes();
        $eDataField_partialViewName = $this->getPartialViewName($eDataField_viewType);
        $eDataField_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.eDataField.stats', $eDataFields_stats);
    
        $eDataFields_permissions = [

            'edit-eDataField' => Auth::user()->can('edit-eDataField'),
            'destroy-eDataField' => Auth::user()->can('destroy-eDataField'),
            'show-eDataField' => Auth::user()->can('show-eDataField'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $eDataFields_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($eDataFields_data as $item) {
                $eDataFields_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'eDataField_viewTypes',
            'eDataField_viewType',
            'eDataFields_data',
            'eDataFields_stats',
            'eDataFields_total',
            'eDataFields_filters',
            'eDataField_instance',
            'eDataField_title',
            'contextKey',
            'eDataFields_permissions',
            'eDataFields_permissionsByItem'
        );
    
        return [
            'eDataFields_data' => $eDataFields_data,
            'eDataFields_stats' => $eDataFields_stats,
            'eDataFields_total' => $eDataFields_total,
            'eDataFields_filters' => $eDataFields_filters,
            'eDataField_instance' => $eDataField_instance,
            'eDataField_viewType' => $eDataField_viewType,
            'eDataField_viewTypes' => $eDataField_viewTypes,
            'eDataField_partialViewName' => $eDataField_partialViewName,
            'contextKey' => $contextKey,
            'eDataField_compact_value' => $compact_value,
            'eDataFields_permissions' => $eDataFields_permissions,
            'eDataFields_permissionsByItem' => $eDataFields_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $eDataField_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $eDataField_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($eDataField_ids as $id) {
            $eDataField = $this->find($id);
            $this->authorize('update', $eDataField);
    
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
        return [
            'displayOrder',
            'name',
            'e_model_id',
            'data_type',
            'displayInTable'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(EDataField $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgGapp\App\Requests\EDataFieldRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'e_data_field',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'displayOrder':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            case 'name':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'e_model_id':
                 $values = (new \Modules\PkgGapp\Services\EModelService())
                    ->getAllForSelect($e->eModel)
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
            case 'data_type':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'displayInTable':
                return $this->computeFieldMeta($e, $field, $meta, 'boolean');

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(EDataField $e, array $changes): EDataField
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
    public function formatDisplayValues(EDataField $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'displayOrder':
                    $html = view('Core::fields_by_type.integer', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'ordre'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'name':
                    // Vue custom définie pour ce champ
                    $html = view('PkgGapp::eDataField.custom.fields.name', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'e_model_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'eModel'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'data_type':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'displayInTable':
                    $html = view('Core::fields_by_type.boolean', [
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
