<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgWidgets\Models\Widget;
use Modules\Core\Services\BaseService;

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
        'section_widget_id',
        'parameters'
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
     * Constructeur de la classe WidgetService.
     */
    public function __construct()
    {
        parent::__construct(new Widget());
        $this->fieldsFilterable = [];
        $this->title = __('PkgWidgets::widget.plural');
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

}
