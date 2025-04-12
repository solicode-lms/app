<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Modules\Core\Models\SysController;
use Modules\Core\Services\BaseService;

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
        'is_active'
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
     * Constructeur de la classe SysControllerService.
     */
    public function __construct()
    {
        parent::__construct(new SysController());
        $this->fieldsFilterable = [];
        $this->title = __('Core::sysController.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sysController');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('sys_module_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysModule.plural"), 'sys_module_id', \Modules\Core\Models\SysModule::class, 'name');
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
     * Trie par date de mise à jour si il n'existe aucune trie
     * @param mixed $query
     * @param mixed $sort
     */
    public function applySort($query, $sort)
    {
        if ($sort) {
            return parent::applySort($query, $sort);
        }else{
            return $query->orderBy("updated_at","desc");
        }
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
        $this->viewState->init('sysController_view_type', $default_view_type);
        $sysController_viewType = $this->viewState->get('sysController_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('sysController_view_type') === 'widgets') {
            $this->viewState->set("filter.sysController.visible", 1);
        }
        
        // Récupération des données
        $sysControllers_data = $this->paginate($params);
        $sysControllers_stats = $this->getsysControllerStats();
        $sysControllers_filters = $this->getFieldsFilterable();
        $sysController_instance = $this->createInstance();
        $sysController_viewTypes = $this->getViewTypes();
        $sysController_partialViewName = $this->getPartialViewName($sysController_viewType);
        $sysController_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.sysController.stats', $sysControllers_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'sysController_viewTypes',
            'sysController_viewType',
            'sysControllers_data',
            'sysControllers_stats',
            'sysControllers_filters',
            'sysController_instance',
            'sysController_title',
            'contextKey'
        );
    
        return [
            'sysControllers_data' => $sysControllers_data,
            'sysControllers_stats' => $sysControllers_stats,
            'sysControllers_filters' => $sysControllers_filters,
            'sysController_instance' => $sysController_instance,
            'sysController_viewType' => $sysController_viewType,
            'sysController_viewTypes' => $sysController_viewTypes,
            'sysController_partialViewName' => $sysController_partialViewName,
            'contextKey' => $contextKey,
            'sysController_compact_value' => $compact_value
        ];
    }

}
