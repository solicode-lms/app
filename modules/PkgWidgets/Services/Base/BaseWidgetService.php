<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgWidgets\Models\Widget;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe WidgetService pour gérer la persistance de l'entité Widget.
 */
class BaseWidgetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour widgets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'icon',
        'name',
        'label',
        'type_id',
        'model_id',
        'operation_id',
        'color',
        'sys_color_id',
        'reference',
        'section_widget_id',
        'parameters'
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
     * Constructeur de la classe WidgetService.
     */
    public function __construct()
    {
        parent::__construct(new Widget());
        $this->fieldsFilterable = [];
        $this->title = __('PkgWidgets::widget.plural');
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
            $widget = $this->find($data['id']);
            $widget->fill($data);
        } else {
            $widget = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($widget->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $widget->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($widget->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($widget->id, $data);
            }
        }

        return $widget;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('widget');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('type_id', $scopeVariables)) {


                    $widgetTypeService = new \Modules\PkgWidgets\Services\WidgetTypeService();
                    $widgetTypeIds = $this->getAvailableFilterValues('type_id');
                    $widgetTypes = $widgetTypeService->getByIds($widgetTypeIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgWidgets::widgetType.plural"), 
                        'type_id', 
                        \Modules\PkgWidgets\Models\WidgetType::class, 
                        'type',
                        $widgetTypes
                    );
                }
            
            
                if (!array_key_exists('roles', $scopeVariables)) {

                    $roleService = new \Modules\PkgAutorisation\Services\RoleService();
                    $roleIds = $this->getAvailableFilterValues('roles.id');
                    $roles = $roleService->getByIds($roleIds);

                    $this->fieldsFilterable[] = $this->generateManyToManyFilter(
                        __("PkgAutorisation::role.plural"), 
                        'role_id', 
                        \Modules\PkgAutorisation\Models\Role::class, 
                        'name',
                        $roles
                    );
                }
            
            
                if (!array_key_exists('section_widget_id', $scopeVariables)) {


                    $sectionWidgetService = new \Modules\PkgWidgets\Services\SectionWidgetService();
                    $sectionWidgetIds = $this->getAvailableFilterValues('section_widget_id');
                    $sectionWidgets = $sectionWidgetService->getByIds($sectionWidgetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgWidgets::sectionWidget.plural"), 
                        'section_widget_id', 
                        \Modules\PkgWidgets\Models\SectionWidget::class, 
                        'titre',
                        $sectionWidgets
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de widget.
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
    public function getWidgetStats(): array
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
            'table' => 'PkgWidgets::widget._table',
            default => 'PkgWidgets::widget._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('widget_view_type', $default_view_type);
        $widget_viewType = $this->viewState->get('widget_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('widget_view_type') === 'widgets') {
            $this->viewState->set("scope.widget.visible", 1);
        }else{
            $this->viewState->remove("scope.widget.visible");
        }
        
        // Récupération des données
        $widgets_data = $this->paginate($params);
        $widgets_stats = $this->getwidgetStats();
        $widgets_total = $this->count();
        $widgets_filters = $this->getFieldsFilterable();
        $widget_instance = $this->createInstance();
        $widget_viewTypes = $this->getViewTypes();
        $widget_partialViewName = $this->getPartialViewName($widget_viewType);
        $widget_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.widget.stats', $widgets_stats);
    
        $widgets_permissions = [

            'edit-widget' => Auth::user()->can('edit-widget'),
            'destroy-widget' => Auth::user()->can('destroy-widget'),
            'show-widget' => Auth::user()->can('show-widget'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $widgets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($widgets_data as $item) {
                $widgets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'widget_viewTypes',
            'widget_viewType',
            'widgets_data',
            'widgets_stats',
            'widgets_total',
            'widgets_filters',
            'widget_instance',
            'widget_title',
            'contextKey',
            'widgets_permissions',
            'widgets_permissionsByItem'
        );
    
        return [
            'widgets_data' => $widgets_data,
            'widgets_stats' => $widgets_stats,
            'widgets_total' => $widgets_total,
            'widgets_filters' => $widgets_filters,
            'widget_instance' => $widget_instance,
            'widget_viewType' => $widget_viewType,
            'widget_viewTypes' => $widget_viewTypes,
            'widget_partialViewName' => $widget_partialViewName,
            'contextKey' => $contextKey,
            'widget_compact_value' => $compact_value,
            'widgets_permissions' => $widgets_permissions,
            'widgets_permissionsByItem' => $widgets_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $widget_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $widget_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($widget_ids as $id) {
            $widget = $this->find($id);
            $this->authorize('update', $widget);
    
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
            'ordre',
            'icon',
            'name',
            'label',
            'type_id',
            'roles',
            'section_widget_id'
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
    public function buildFieldMeta(Widget $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgWidgets\App\Requests\WidgetRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'widget',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'ordre':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            case 'icon':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'name':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'label':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'type_id':
                 $values = (new \Modules\PkgWidgets\Services\WidgetTypeService())
                    ->getAllForSelect($e->type)
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
            case 'roles':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'section_widget_id':
                 $values = (new \Modules\PkgWidgets\Services\SectionWidgetService())
                    ->getAllForSelect($e->sectionWidget)
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
    public function applyInlinePatch(Widget $e, array $changes): Widget
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
    public function formatDisplayValues(Widget $e, array $fields): array
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
                case 'icon':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'icone'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'name':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'badge'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'label':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'type_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'type'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'roles':
                    // fallback string simple
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'section_widget_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'sectionWidget'
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
