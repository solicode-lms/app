<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatsRealisationProjetService pour gérer la persistance de l'entité EtatsRealisationProjet.
 */
class BaseEtatsRealisationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatsRealisationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'formateur_id',
        'titre',
        'description',
        'sys_color_id',
        'workflow_projet_id',
        'is_editable_by_formateur'
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
     * Constructeur de la classe EtatsRealisationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new EtatsRealisationProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationProjets::etatsRealisationProjet.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatsRealisationProjet');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }

        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
        }

        if (!array_key_exists('workflow_projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgRealisationProjets::workflowProjet.plural"), 'workflow_projet_id', \Modules\PkgRealisationProjets\Models\WorkflowProjet::class, 'code');
        }

    }

    /**
     * Crée une nouvelle instance de etatsRealisationProjet.
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
    public function getEtatsRealisationProjetStats(): array
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
            'table' => 'PkgRealisationProjets::etatsRealisationProjet._table',
            default => 'PkgRealisationProjets::etatsRealisationProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatsRealisationProjet_view_type', $default_view_type);
        $etatsRealisationProjet_viewType = $this->viewState->get('etatsRealisationProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatsRealisationProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.etatsRealisationProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.etatsRealisationProjet.visible");
        }
        
        // Récupération des données
        $etatsRealisationProjets_data = $this->paginate($params);
        $etatsRealisationProjets_stats = $this->getetatsRealisationProjetStats();
        $etatsRealisationProjets_filters = $this->getFieldsFilterable();
        $etatsRealisationProjet_instance = $this->createInstance();
        $etatsRealisationProjet_viewTypes = $this->getViewTypes();
        $etatsRealisationProjet_partialViewName = $this->getPartialViewName($etatsRealisationProjet_viewType);
        $etatsRealisationProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatsRealisationProjet.stats', $etatsRealisationProjets_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatsRealisationProjet_viewTypes',
            'etatsRealisationProjet_viewType',
            'etatsRealisationProjets_data',
            'etatsRealisationProjets_stats',
            'etatsRealisationProjets_filters',
            'etatsRealisationProjet_instance',
            'etatsRealisationProjet_title',
            'contextKey'
        );
    
        return [
            'etatsRealisationProjets_data' => $etatsRealisationProjets_data,
            'etatsRealisationProjets_stats' => $etatsRealisationProjets_stats,
            'etatsRealisationProjets_filters' => $etatsRealisationProjets_filters,
            'etatsRealisationProjet_instance' => $etatsRealisationProjet_instance,
            'etatsRealisationProjet_viewType' => $etatsRealisationProjet_viewType,
            'etatsRealisationProjet_viewTypes' => $etatsRealisationProjet_viewTypes,
            'etatsRealisationProjet_partialViewName' => $etatsRealisationProjet_partialViewName,
            'contextKey' => $contextKey,
            'etatsRealisationProjet_compact_value' => $compact_value
        ];
    }

}
