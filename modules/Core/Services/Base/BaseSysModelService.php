<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Modules\Core\Models\SysModel;
use Modules\Core\Services\BaseService;

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
        'icone',
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
     * Constructeur de la classe SysModelService.
     */
    public function __construct()
    {
        parent::__construct(new SysModel());
        $this->fieldsFilterable = [];
        $this->title = __('Core::sysModel.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sysModel');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('sys_module_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysModule.plural"), 'sys_module_id', \Modules\Core\Models\SysModule::class, 'name');
        }



        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
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
        $this->viewState->init('sysModel_view_type', $default_view_type);
        $sysModel_viewType = $this->viewState->get('sysModel_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('sysModel_view_type') === 'widgets') {
            $this->viewState->set("filter.sysModel.visible", 1);
        }
        
        // Récupération des données
        $sysModels_data = $this->paginate($params);
        $sysModels_stats = $this->getsysModelStats();
        $sysModels_filters = $this->getFieldsFilterable();
        $sysModel_instance = $this->createInstance();
        $sysModel_viewTypes = $this->getViewTypes();
        $sysModel_partialViewName = $this->getPartialViewName($sysModel_viewType);
        $sysModel_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.sysModel.stats', $sysModels_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'sysModel_viewTypes',
            'sysModel_viewType',
            'sysModels_data',
            'sysModels_stats',
            'sysModels_filters',
            'sysModel_instance',
            'sysModel_title',
            'contextKey'
        );
    
        return [
            'sysModels_data' => $sysModels_data,
            'sysModels_stats' => $sysModels_stats,
            'sysModels_filters' => $sysModels_filters,
            'sysModel_instance' => $sysModel_instance,
            'sysModel_viewType' => $sysModel_viewType,
            'sysModel_viewTypes' => $sysModel_viewTypes,
            'sysModel_partialViewName' => $sysModel_partialViewName,
            'contextKey' => $contextKey,
            'sysModel_compact_value' => $compact_value
        ];
    }

}
