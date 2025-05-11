<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationProjetService pour gérer la persistance de l'entité RealisationProjet.
 */
class BaseRealisationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'affectation_projet_id',
        'apprenant_id',
        'date_debut',
        'date_fin',
        'etats_realisation_projet_id',
        'rapport'
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
     * Constructeur de la classe RealisationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationProjets::realisationProjet.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationProjet');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('affectation_projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgRealisationProjets::affectationProjet.plural"), 'affectation_projet_id', \Modules\PkgRealisationProjets\Models\AffectationProjet::class, 'id');
        }

        if (!array_key_exists('apprenant_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgApprenants::apprenant.plural"), 'apprenant_id', \Modules\PkgApprenants\Models\Apprenant::class, 'nom');
        }

        if (!array_key_exists('etats_realisation_projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgRealisationProjets::etatsRealisationProjet.plural"), 'etats_realisation_projet_id', \Modules\PkgRealisationProjets\Models\EtatsRealisationProjet::class, 'titre');
        }

    }

    /**
     * Crée une nouvelle instance de realisationProjet.
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
    public function getRealisationProjetStats(): array
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
            'table' => 'PkgRealisationProjets::realisationProjet._table',
            default => 'PkgRealisationProjets::realisationProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('realisationProjet_view_type', $default_view_type);
        $realisationProjet_viewType = $this->viewState->get('realisationProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationProjet.visible");
        }
        
        // Récupération des données
        $realisationProjets_data = $this->paginate($params);
        $realisationProjets_stats = $this->getrealisationProjetStats();
        $realisationProjets_filters = $this->getFieldsFilterable();
        $realisationProjet_instance = $this->createInstance();
        $realisationProjet_viewTypes = $this->getViewTypes();
        $realisationProjet_partialViewName = $this->getPartialViewName($realisationProjet_viewType);
        $realisationProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationProjet.stats', $realisationProjets_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationProjet_viewTypes',
            'realisationProjet_viewType',
            'realisationProjets_data',
            'realisationProjets_stats',
            'realisationProjets_filters',
            'realisationProjet_instance',
            'realisationProjet_title',
            'contextKey'
        );
    
        return [
            'realisationProjets_data' => $realisationProjets_data,
            'realisationProjets_stats' => $realisationProjets_stats,
            'realisationProjets_filters' => $realisationProjets_filters,
            'realisationProjet_instance' => $realisationProjet_instance,
            'realisationProjet_viewType' => $realisationProjet_viewType,
            'realisationProjet_viewTypes' => $realisationProjet_viewTypes,
            'realisationProjet_partialViewName' => $realisationProjet_partialViewName,
            'contextKey' => $contextKey,
            'realisationProjet_compact_value' => $compact_value
        ];
    }

}
