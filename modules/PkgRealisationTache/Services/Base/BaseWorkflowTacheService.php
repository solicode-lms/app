<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgRealisationTache\Models\WorkflowTache;
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
        'ordre',
        'code',
        'titre',
        'description',
        'is_editable_only_by_formateur',
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
        $this->title = __('PkgRealisationTache::workflowTache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('workflowTache');
        $this->fieldsFilterable = [];
        
            
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
            'table' => 'PkgRealisationTache::workflowTache._table',
            default => 'PkgRealisationTache::workflowTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('workflowTache_view_type', $default_view_type);
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
        $workflowTaches_total = $this->count();
        $workflowTaches_filters = $this->getFieldsFilterable();
        $workflowTache_instance = $this->createInstance();
        $workflowTache_viewTypes = $this->getViewTypes();
        $workflowTache_partialViewName = $this->getPartialViewName($workflowTache_viewType);
        $workflowTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.workflowTache.stats', $workflowTaches_stats);
    
        $workflowTaches_permissions = [

            'edit-workflowTache' => Auth::user()->can('edit-workflowTache'),
            'destroy-workflowTache' => Auth::user()->can('destroy-workflowTache'),
            'show-workflowTache' => Auth::user()->can('show-workflowTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $workflowTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($workflowTaches_data as $item) {
                $workflowTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'workflowTache_viewTypes',
            'workflowTache_viewType',
            'workflowTaches_data',
            'workflowTaches_stats',
            'workflowTaches_total',
            'workflowTaches_filters',
            'workflowTache_instance',
            'workflowTache_title',
            'contextKey',
            'workflowTaches_permissions',
            'workflowTaches_permissionsByItem'
        );
    
        return [
            'workflowTaches_data' => $workflowTaches_data,
            'workflowTaches_stats' => $workflowTaches_stats,
            'workflowTaches_total' => $workflowTaches_total,
            'workflowTaches_filters' => $workflowTaches_filters,
            'workflowTache_instance' => $workflowTache_instance,
            'workflowTache_viewType' => $workflowTache_viewType,
            'workflowTache_viewTypes' => $workflowTache_viewTypes,
            'workflowTache_partialViewName' => $workflowTache_partialViewName,
            'contextKey' => $contextKey,
            'workflowTache_compact_value' => $compact_value,
            'workflowTaches_permissions' => $workflowTaches_permissions,
            'workflowTaches_permissionsByItem' => $workflowTaches_permissionsByItem
        ];
    }

}
