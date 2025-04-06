<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

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
        'name',
        'label',
        'type_id',
        'model_id',
        'operation_id',
        'color',
        'icon',
        'sys_color_id',
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
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('widget');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('type_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgWidgets::widgetType.plural"), 'type_id', \Modules\PkgWidgets\Models\WidgetType::class, 'type');
        }
        if (!array_key_exists('roles', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToManyFilter(__("PkgAutorisation::role.plural"), 'role_id', \Modules\PkgAutorisation\Models\Role::class, 'name');
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
        $this->viewState->init('widget_view_type', $default_view_type);
        $viewType = $this->viewState->get('widget_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('widget_view_type') === 'widgets') {
            $this->viewState->set("filter.widget.visible", 1);
        }
        
        // Récupération des données
        $widgets_data = $this->paginate($params);
        $widgets_stats = $this->getwidgetStats();
        $widgets_filters = $this->getFieldsFilterable();
        $widget_instance = $this->createInstance();
        $viewTypes = $this->getViewTypes();
        $partialViewName = $this->getPartialViewName($viewType);
    
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.widget.stats', $widgets_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'viewTypes',
            'viewType',
            'widgets_data',
            'widgets_stats',
            'widgets_filters',
            'widget_instance'
        );
    
        return [
            'widgets_data' => $widgets_data,
            'widgets_stats' => $widgets_stats,
            'widgets_filters' => $widgets_filters,
            'widget_instance' => $widget_instance,
            'widget_viewType' => $viewType,
            'widget_viewTypes' => $viewTypes,
            'widget_partialViewName' => $partialViewName,
            'widget_compact_value' => $compact_value
        ];
    }

}
