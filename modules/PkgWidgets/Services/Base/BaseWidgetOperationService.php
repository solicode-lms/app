<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

use Modules\PkgWidgets\Models\WidgetOperation;
use Modules\Core\Services\BaseService;

/**
 * Classe WidgetOperationService pour gérer la persistance de l'entité WidgetOperation.
 */
class BaseWidgetOperationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour widgetOperations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'operation',
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
     * Constructeur de la classe WidgetOperationService.
     */
    public function __construct()
    {
        parent::__construct(new WidgetOperation());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('widgetOperation');
        $this->fieldsFilterable = [];
    
    }

    /**
     * Crée une nouvelle instance de widgetOperation.
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
    public function getWidgetOperationStats(): array
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
            'table' => 'PkgWidgets::widgetOperation._table',
            default => 'PkgWidgets::widgetOperation._table',
        };
    }

    public function prepareDataForIndexView(array $params = [], ?string $viewType = null): array
    {
        $data = $this->paginate($params);
        $stats = $this->getwidgetOperationStats();
        $this->viewState->set('stats.widgetOperation.stats'  , $stats);

        return [
            'widgetOperations_data' =>$data,
            'widgetOperations_stats' => $stats,
            'widgetOperations_filters' => $this->getFieldsFilterable(),
            'widgetOperation_instance' => $this->createInstance(),
            'viewType' => $viewType ?? 'table',
            'partialViewName' => $this->getPartialViewName($viewType ?? 'table'),
            'viewTypes' => $this->getViewTypes(),
        ];
    }

}
