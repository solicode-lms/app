<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgWidgets\Models\WidgetType;
use Modules\Core\Services\BaseService;

/**
 * Classe WidgetTypeService pour gérer la persistance de l'entité WidgetType.
 */
class BaseWidgetTypeService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour widgetTypes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'type',
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
     * Constructeur de la classe WidgetTypeService.
     */
    public function __construct()
    {
        parent::__construct(new WidgetType());
        $this->fieldsFilterable = [];
        $this->title = __('PkgWidgets::widgetType.plural');
    }


    public function dataCalcul($widgetType)
    {
        // En Cas d'édit
        if(isset($widgetType->id)){
          
        }
      
        return $widgetType;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('widgetType');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de widgetType.
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
    public function getWidgetTypeStats(): array
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
            'table' => 'PkgWidgets::widgetType._table',
            default => 'PkgWidgets::widgetType._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('widgetType_view_type', $default_view_type);
        $widgetType_viewType = $this->viewState->get('widgetType_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('widgetType_view_type') === 'widgets') {
            $this->viewState->set("scope.widgetType.visible", 1);
        }else{
            $this->viewState->remove("scope.widgetType.visible");
        }
        
        // Récupération des données
        $widgetTypes_data = $this->paginate($params);
        $widgetTypes_stats = $this->getwidgetTypeStats();
        $widgetTypes_total = $this->count();
        $widgetTypes_filters = $this->getFieldsFilterable();
        $widgetType_instance = $this->createInstance();
        $widgetType_viewTypes = $this->getViewTypes();
        $widgetType_partialViewName = $this->getPartialViewName($widgetType_viewType);
        $widgetType_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.widgetType.stats', $widgetTypes_stats);
    
        $widgetTypes_permissions = [

            'edit-widgetType' => Auth::user()->can('edit-widgetType'),
            'destroy-widgetType' => Auth::user()->can('destroy-widgetType'),
            'show-widgetType' => Auth::user()->can('show-widgetType'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $widgetTypes_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($widgetTypes_data as $item) {
                $widgetTypes_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'widgetType_viewTypes',
            'widgetType_viewType',
            'widgetTypes_data',
            'widgetTypes_stats',
            'widgetTypes_total',
            'widgetTypes_filters',
            'widgetType_instance',
            'widgetType_title',
            'contextKey',
            'widgetTypes_permissions',
            'widgetTypes_permissionsByItem'
        );
    
        return [
            'widgetTypes_data' => $widgetTypes_data,
            'widgetTypes_stats' => $widgetTypes_stats,
            'widgetTypes_total' => $widgetTypes_total,
            'widgetTypes_filters' => $widgetTypes_filters,
            'widgetType_instance' => $widgetType_instance,
            'widgetType_viewType' => $widgetType_viewType,
            'widgetType_viewTypes' => $widgetType_viewTypes,
            'widgetType_partialViewName' => $widgetType_partialViewName,
            'contextKey' => $contextKey,
            'widgetType_compact_value' => $compact_value,
            'widgetTypes_permissions' => $widgetTypes_permissions,
            'widgetTypes_permissionsByItem' => $widgetTypes_permissionsByItem
        ];
    }

}
