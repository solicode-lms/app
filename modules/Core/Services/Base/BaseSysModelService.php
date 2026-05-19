<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\Core\Models\SysModel;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe SysModelService pour gérer la persistance de l'entité SysModel.
 */
class BaseSysModelService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sysModels.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'model',
        'sys_module_id',
        'sys_color_id',
        'reference',
        'icone',
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
     * Constructeur de la classe SysModelService.
     */
    public function __construct()
    {
        parent::__construct(new SysModel());
        $this->fieldsFilterable = [];
        $this->title = __('Core::sysModel.plural');
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
            $sysModel = $this->find($data['id']);
            $sysModel->fill($data);
        } else {
            $sysModel = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($sysModel->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $sysModel->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($sysModel->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($sysModel->id, $data);
            }
        }

        return $sysModel;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sysModel');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('sys_module_id', $scopeVariables)) {


                    $sysModuleService = new \Modules\Core\Services\SysModuleService();
                    $sysModuleIds = $this->getAvailableFilterValues('sys_module_id');
                    $sysModules = $sysModuleService->getByIds($sysModuleIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("Core::sysModule.plural"), 
                        'sys_module_id', 
                        \Modules\Core\Models\SysModule::class, 
                        'name',
                        $sysModules
                    );
                }
            
            
                if (!array_key_exists('sys_color_id', $scopeVariables)) {


                    $sysColorService = new \Modules\Core\Services\SysColorService();
                    $sysColorIds = $this->getAvailableFilterValues('sys_color_id');
                    $sysColors = $sysColorService->getByIds($sysColorIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("Core::sysColor.plural"), 
                        'sys_color_id', 
                        \Modules\Core\Models\SysColor::class, 
                        'name',
                        $sysColors
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de sysModel.
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
    public function getSysModelStats(): array
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
            'table' => 'Core::sysModel._table',
            default => 'Core::sysModel._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('sysModel_view_type', $default_view_type);
        $sysModel_viewType = $this->viewState->get('sysModel_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('sysModel_view_type') === 'widgets') {
            $this->viewState->set("scope.sysModel.visible", 1);
        }else{
            $this->viewState->remove("scope.sysModel.visible");
        }
        
        // Récupération des données
        $sysModels_data = $this->paginate($params);
        $sysModels_stats = $this->getsysModelStats();
        $sysModels_total = $this->count();
        $sysModels_filters = $this->getFieldsFilterable();
        $sysModel_instance = $this->createInstance();
        $sysModel_viewTypes = $this->getViewTypes();
        $sysModel_partialViewName = $this->getPartialViewName($sysModel_viewType);
        $sysModel_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.sysModel.stats', $sysModels_stats);
    
        $sysModels_permissions = [

            'edit-sysModel' => Auth::user()->can('edit-sysModel'),
            'destroy-sysModel' => Auth::user()->can('destroy-sysModel'),
            'show-sysModel' => Auth::user()->can('show-sysModel'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $sysModels_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($sysModels_data as $item) {
                $sysModels_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'sysModel_viewTypes',
            'sysModel_viewType',
            'sysModels_data',
            'sysModels_stats',
            'sysModels_total',
            'sysModels_filters',
            'sysModel_instance',
            'sysModel_title',
            'contextKey',
            'sysModels_permissions',
            'sysModels_permissionsByItem'
        );
    
        return [
            'sysModels_data' => $sysModels_data,
            'sysModels_stats' => $sysModels_stats,
            'sysModels_total' => $sysModels_total,
            'sysModels_filters' => $sysModels_filters,
            'sysModel_instance' => $sysModel_instance,
            'sysModel_viewType' => $sysModel_viewType,
            'sysModel_viewTypes' => $sysModel_viewTypes,
            'sysModel_partialViewName' => $sysModel_partialViewName,
            'contextKey' => $contextKey,
            'sysModel_compact_value' => $compact_value,
            'sysModels_permissions' => $sysModels_permissions,
            'sysModels_permissionsByItem' => $sysModels_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $sysModel_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $sysModel_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($sysModel_ids as $id) {
            $sysModel = $this->find($id);
            $this->authorize('update', $sysModel);
    
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
            'sys_module_id',
            'sys_color_id',
            'icone'
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
    public function buildFieldMeta(SysModel $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\Core\App\Requests\SysModelRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'sys_model',
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
            case 'sys_module_id':
                 $values = (new \Modules\Core\Services\SysModuleService())
                    ->getAllForSelect($e->sysModule)
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
            case 'sys_color_id':
                 $values = (new \Modules\Core\Services\SysColorService())
                    ->getAllForSelect($e->sysColor)
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
            case 'icone':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(SysModel $e, array $changes): SysModel
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
    public function formatDisplayValues(SysModel $e, array $fields): array
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
                case 'sys_module_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'sysModule'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'sys_color_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'couleur',
                        'relationName' => 'sysColor'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'icone':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'icone'
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
