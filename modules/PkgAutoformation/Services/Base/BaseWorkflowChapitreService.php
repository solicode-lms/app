<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Modules\PkgAutoformation\Models\WorkflowChapitre;
use Modules\Core\Services\BaseService;

/**
 * Classe WorkflowChapitreService pour gérer la persistance de l'entité WorkflowChapitre.
 */
class BaseWorkflowChapitreService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour workflowChapitres.
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
     * Constructeur de la classe WorkflowChapitreService.
     */
    public function __construct()
    {
        parent::__construct(new WorkflowChapitre());
        $this->fieldsFilterable = [];
        $this->title = __('PkgAutoformation::workflowChapitre.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('workflowChapitre');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
        }
    }

    /**
     * Crée une nouvelle instance de workflowChapitre.
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
    public function getWorkflowChapitreStats(): array
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
            'table' => 'PkgAutoformation::workflowChapitre._table',
            default => 'PkgAutoformation::workflowChapitre._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('workflowChapitre_view_type', $default_view_type);
        $workflowChapitre_viewType = $this->viewState->get('workflowChapitre_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('workflowChapitre_view_type') === 'widgets') {
            $this->viewState->set("filter.workflowChapitre.visible", 1);
        }
        
        // Récupération des données
        $workflowChapitres_data = $this->paginate($params);
        $workflowChapitres_stats = $this->getworkflowChapitreStats();
        $workflowChapitres_filters = $this->getFieldsFilterable();
        $workflowChapitre_instance = $this->createInstance();
        $workflowChapitre_viewTypes = $this->getViewTypes();
        $workflowChapitre_partialViewName = $this->getPartialViewName($workflowChapitre_viewType);
        $workflowChapitre_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.workflowChapitre.stats', $workflowChapitres_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'workflowChapitre_viewTypes',
            'workflowChapitre_viewType',
            'workflowChapitres_data',
            'workflowChapitres_stats',
            'workflowChapitres_filters',
            'workflowChapitre_instance',
            'workflowChapitre_title',
            'contextKey'
        );
    
        return [
            'workflowChapitres_data' => $workflowChapitres_data,
            'workflowChapitres_stats' => $workflowChapitres_stats,
            'workflowChapitres_filters' => $workflowChapitres_filters,
            'workflowChapitre_instance' => $workflowChapitre_instance,
            'workflowChapitre_viewType' => $workflowChapitre_viewType,
            'workflowChapitre_viewTypes' => $workflowChapitre_viewTypes,
            'workflowChapitre_partialViewName' => $workflowChapitre_partialViewName,
            'contextKey' => $contextKey,
            'workflowChapitre_compact_value' => $compact_value
        ];
    }

}
