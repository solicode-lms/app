<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\Core\Models\SysController;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe SysControllerService pour gérer la persistance de l'entité SysController.
 */
class BaseSysControllerService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sysControllers.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'sys_module_id',
        'name',
        'slug',
        'description',
        'is_active',
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
     * Constructeur de la classe SysControllerService.
     */
    public function __construct()
    {
        parent::__construct(new SysController());
        $this->fieldsFilterable = [];
        $this->title = __('Core::sysController.plural');
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
            $sysController = $this->find($data['id']);
            $sysController->fill($data);
        } else {
            $sysController = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($sysController->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $sysController->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($sysController->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($sysController->id, $data);
            }
        }

        return $sysController;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sysController');
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
            



    }


    /**
     * Crée une nouvelle instance de sysController.
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
    public function getSysControllerStats(): array
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
            'table' => 'Core::sysController._table',
            default => 'Core::sysController._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('sysController_view_type', $default_view_type);
        $sysController_viewType = $this->viewState->get('sysController_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('sysController_view_type') === 'widgets') {
            $this->viewState->set("scope.sysController.visible", 1);
        }else{
            $this->viewState->remove("scope.sysController.visible");
        }
        
        // Récupération des données
        $sysControllers_data = $this->paginate($params);
        $sysControllers_stats = $this->getsysControllerStats();
        $sysControllers_total = $this->count();
        $sysControllers_filters = $this->getFieldsFilterable();
        $sysController_instance = $this->createInstance();
        $sysController_viewTypes = $this->getViewTypes();
        $sysController_partialViewName = $this->getPartialViewName($sysController_viewType);
        $sysController_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.sysController.stats', $sysControllers_stats);
    
        $sysControllers_permissions = [

            'edit-sysController' => Auth::user()->can('edit-sysController'),
            'destroy-sysController' => Auth::user()->can('destroy-sysController'),
            'show-sysController' => Auth::user()->can('show-sysController'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $sysControllers_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($sysControllers_data as $item) {
                $sysControllers_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'sysController_viewTypes',
            'sysController_viewType',
            'sysControllers_data',
            'sysControllers_stats',
            'sysControllers_total',
            'sysControllers_filters',
            'sysController_instance',
            'sysController_title',
            'contextKey',
            'sysControllers_permissions',
            'sysControllers_permissionsByItem'
        );
    
        return [
            'sysControllers_data' => $sysControllers_data,
            'sysControllers_stats' => $sysControllers_stats,
            'sysControllers_total' => $sysControllers_total,
            'sysControllers_filters' => $sysControllers_filters,
            'sysController_instance' => $sysController_instance,
            'sysController_viewType' => $sysController_viewType,
            'sysController_viewTypes' => $sysController_viewTypes,
            'sysController_partialViewName' => $sysController_partialViewName,
            'contextKey' => $contextKey,
            'sysController_compact_value' => $compact_value,
            'sysControllers_permissions' => $sysControllers_permissions,
            'sysControllers_permissionsByItem' => $sysControllers_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $sysController_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $sysController_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($sysController_ids as $id) {
            $sysController = $this->find($id);
            $this->authorize('update', $sysController);
    
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
            'sys_module_id',
            'name',
            'is_active',
            'Permission'
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
    public function buildFieldMeta(SysController $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\Core\App\Requests\SysControllerRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'sys_controller',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
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
            case 'name':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'is_active':
                return $this->computeFieldMeta($e, $field, $meta, 'boolean');

            case 'Permission':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(SysController $e, array $changes): SysController
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
    public function formatDisplayValues(SysController $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'sys_module_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'sysModule'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'name':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'is_active':
                    $html = view('Core::fields_by_type.boolean', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'Permission':
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
