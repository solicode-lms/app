<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\WorkflowTache;
use Modules\Core\Services\BaseService;

/**
 * Classe WorkflowTacheService pour gérer la persistance de l'entité WorkflowTache.
 */
class BaseWorkflowTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour workflowTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'titre',
        'description',
        'ordre',
        'sys_color_id'
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
     * Constructeur de la classe WorkflowTacheService.
     */
    public function __construct()
    {
        parent::__construct(new WorkflowTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGestionTaches::workflowTache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('workflowTache');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
        }

    }

    /**
     * Crée une nouvelle instance de workflowTache.
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
    public function getWorkflowTacheStats(): array
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
            'table' => 'PkgGestionTaches::workflowTache._table',
            default => 'PkgGestionTaches::workflowTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('workflowTache_view_type', $default_view_type);
        $workflowTache_viewType = $this->viewState->get('workflowTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('workflowTache_view_type') === 'widgets') {
            $this->viewState->set("scope.workflowTache.visible", 1);
        }else{
            $this->viewState->remove("scope.workflowTache.visible");
        }
        
        // Récupération des données
        $workflowTaches_data = $this->paginate($params);
        $workflowTaches_stats = $this->getworkflowTacheStats();
        $workflowTaches_filters = $this->getFieldsFilterable();
        $workflowTache_instance = $this->createInstance();
        $workflowTache_viewTypes = $this->getViewTypes();
        $workflowTache_partialViewName = $this->getPartialViewName($workflowTache_viewType);
        $workflowTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.workflowTache.stats', $workflowTaches_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'workflowTache_viewTypes',
            'workflowTache_viewType',
            'workflowTaches_data',
            'workflowTaches_stats',
            'workflowTaches_filters',
            'workflowTache_instance',
            'workflowTache_title',
            'contextKey'
        );
    
        return [
            'workflowTaches_data' => $workflowTaches_data,
            'workflowTaches_stats' => $workflowTaches_stats,
            'workflowTaches_filters' => $workflowTaches_filters,
            'workflowTache_instance' => $workflowTache_instance,
            'workflowTache_viewType' => $workflowTache_viewType,
            'workflowTache_viewTypes' => $workflowTache_viewTypes,
            'workflowTache_partialViewName' => $workflowTache_partialViewName,
            'contextKey' => $contextKey,
            'workflowTache_compact_value' => $compact_value
        ];
    }

}
