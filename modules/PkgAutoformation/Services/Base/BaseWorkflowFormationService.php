<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Modules\PkgAutoformation\Models\WorkflowFormation;
use Modules\Core\Services\BaseService;

/**
 * Classe WorkflowFormationService pour gérer la persistance de l'entité WorkflowFormation.
 */
class BaseWorkflowFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour workflowFormations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'titre',
        'sys_color_id',
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
     * Constructeur de la classe WorkflowFormationService.
     */
    public function __construct()
    {
        parent::__construct(new WorkflowFormation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgAutoformation::workflowFormation.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('workflowFormation');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
        }
    }

    /**
     * Crée une nouvelle instance de workflowFormation.
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
    public function getWorkflowFormationStats(): array
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
            'table' => 'PkgAutoformation::workflowFormation._table',
            default => 'PkgAutoformation::workflowFormation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('workflowFormation_view_type', $default_view_type);
        $workflowFormation_viewType = $this->viewState->get('workflowFormation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('workflowFormation_view_type') === 'widgets') {
            $this->viewState->set("filter.workflowFormation.visible", 1);
        }
        
        // Récupération des données
        $workflowFormations_data = $this->paginate($params);
        $workflowFormations_stats = $this->getworkflowFormationStats();
        $workflowFormations_filters = $this->getFieldsFilterable();
        $workflowFormation_instance = $this->createInstance();
        $workflowFormation_viewTypes = $this->getViewTypes();
        $workflowFormation_partialViewName = $this->getPartialViewName($workflowFormation_viewType);
        $workflowFormation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.workflowFormation.stats', $workflowFormations_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'workflowFormation_viewTypes',
            'workflowFormation_viewType',
            'workflowFormations_data',
            'workflowFormations_stats',
            'workflowFormations_filters',
            'workflowFormation_instance',
            'workflowFormation_title',
            'contextKey'
        );
    
        return [
            'workflowFormations_data' => $workflowFormations_data,
            'workflowFormations_stats' => $workflowFormations_stats,
            'workflowFormations_filters' => $workflowFormations_filters,
            'workflowFormation_instance' => $workflowFormation_instance,
            'workflowFormation_viewType' => $workflowFormation_viewType,
            'workflowFormation_viewTypes' => $workflowFormation_viewTypes,
            'workflowFormation_partialViewName' => $workflowFormation_partialViewName,
            'contextKey' => $contextKey,
            'workflowFormation_compact_value' => $compact_value
        ];
    }

}
