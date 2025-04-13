<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Modules\PkgAutoformation\Models\EtatFormation;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatFormationService pour gérer la persistance de l'entité EtatFormation.
 */
class BaseEtatFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatFormations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'workflow_formation_id',
        'sys_color_id',
        'is_editable_only_by_formateur',
        'formateur_id',
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
     * Constructeur de la classe EtatFormationService.
     */
    public function __construct()
    {
        parent::__construct(new EtatFormation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgAutoformation::etatFormation.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatFormation');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('workflow_formation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::workflowFormation.plural"), 'workflow_formation_id', \Modules\PkgAutoformation\Models\WorkflowFormation::class, 'code');
        }

        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
        }

        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }

    }

    /**
     * Crée une nouvelle instance de etatFormation.
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
    public function getEtatFormationStats(): array
    {

        $stats = $this->initStats();

        // Ajouter les statistiques du propriétaire
        //$contexteState = $this->getContextState();
        // if ($contexteState !== null) {
        //     $stats[] = $contexteState;
        // }
        

        return $stats;
    }

    public function getContextState()
    {
        $value = $this->viewState->generateTitleFromVariables();
        return [
                "icon" => "fas fa-filter",
                "label" => "Filtre",
                "value" =>  $value
        ];
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
            'table' => 'PkgAutoformation::etatFormation._table',
            default => 'PkgAutoformation::etatFormation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('etatFormation_view_type', $default_view_type);
        $etatFormation_viewType = $this->viewState->get('etatFormation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatFormation_view_type') === 'widgets') {
            $this->viewState->set("scope.etatFormation.visible", 1);
        }else{
            $this->viewState->remove("scope.etatFormation.visible");
        }
        
        // Récupération des données
        $etatFormations_data = $this->paginate($params);
        $etatFormations_stats = $this->getetatFormationStats();
        $etatFormations_filters = $this->getFieldsFilterable();
        $etatFormation_instance = $this->createInstance();
        $etatFormation_viewTypes = $this->getViewTypes();
        $etatFormation_partialViewName = $this->getPartialViewName($etatFormation_viewType);
        $etatFormation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatFormation.stats', $etatFormations_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatFormation_viewTypes',
            'etatFormation_viewType',
            'etatFormations_data',
            'etatFormations_stats',
            'etatFormations_filters',
            'etatFormation_instance',
            'etatFormation_title',
            'contextKey'
        );
    
        return [
            'etatFormations_data' => $etatFormations_data,
            'etatFormations_stats' => $etatFormations_stats,
            'etatFormations_filters' => $etatFormations_filters,
            'etatFormation_instance' => $etatFormation_instance,
            'etatFormation_viewType' => $etatFormation_viewType,
            'etatFormation_viewTypes' => $etatFormation_viewTypes,
            'etatFormation_partialViewName' => $etatFormation_partialViewName,
            'contextKey' => $contextKey,
            'etatFormation_compact_value' => $compact_value
        ];
    }

}
