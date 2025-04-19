<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Modules\PkgRealisationProjets\Models\WorkflowProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe WorkflowProjetService pour gérer la persistance de l'entité WorkflowProjet.
 */
class BaseWorkflowProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour workflowProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'code',
        'titre',
        'description',
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
     * Constructeur de la classe WorkflowProjetService.
     */
    public function __construct()
    {
        parent::__construct(new WorkflowProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationProjets::workflowProjet.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('workflowProjet');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
        }

    }

    /**
     * Crée une nouvelle instance de workflowProjet.
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
    public function getWorkflowProjetStats(): array
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
            'table' => 'PkgRealisationProjets::workflowProjet._table',
            default => 'PkgRealisationProjets::workflowProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('workflowProjet_view_type', $default_view_type);
        $workflowProjet_viewType = $this->viewState->get('workflowProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('workflowProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.workflowProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.workflowProjet.visible");
        }
        
        // Récupération des données
        $workflowProjets_data = $this->paginate($params);
        $workflowProjets_stats = $this->getworkflowProjetStats();
        $workflowProjets_filters = $this->getFieldsFilterable();
        $workflowProjet_instance = $this->createInstance();
        $workflowProjet_viewTypes = $this->getViewTypes();
        $workflowProjet_partialViewName = $this->getPartialViewName($workflowProjet_viewType);
        $workflowProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.workflowProjet.stats', $workflowProjets_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'workflowProjet_viewTypes',
            'workflowProjet_viewType',
            'workflowProjets_data',
            'workflowProjets_stats',
            'workflowProjets_filters',
            'workflowProjet_instance',
            'workflowProjet_title',
            'contextKey'
        );
    
        return [
            'workflowProjets_data' => $workflowProjets_data,
            'workflowProjets_stats' => $workflowProjets_stats,
            'workflowProjets_filters' => $workflowProjets_filters,
            'workflowProjet_instance' => $workflowProjet_instance,
            'workflowProjet_viewType' => $workflowProjet_viewType,
            'workflowProjet_viewTypes' => $workflowProjet_viewTypes,
            'workflowProjet_partialViewName' => $workflowProjet_partialViewName,
            'contextKey' => $contextKey,
            'workflowProjet_compact_value' => $compact_value
        ];
    }

}
