<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe AffectationProjetService pour gérer la persistance de l'entité AffectationProjet.
 */
class BaseAffectationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour affectationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'projet_id',
        'groupe_id',
        'annee_formation_id',
        'date_debut',
        'date_fin',
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
     * Constructeur de la classe AffectationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new AffectationProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationProjets::affectationProjet.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('affectationProjet');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCreationProjet::projet.plural"), 'projet_id', \Modules\PkgCreationProjet\Models\Projet::class, 'titre');
        }

        if (!array_key_exists('groupe_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgApprenants::groupe.plural"), 'groupe_id', \Modules\PkgApprenants\Models\Groupe::class, 'code');
        }

    }

    /**
     * Crée une nouvelle instance de affectationProjet.
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
    public function getAffectationProjetStats(): array
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
            'table' => 'PkgRealisationProjets::affectationProjet._table',
            default => 'PkgRealisationProjets::affectationProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('affectationProjet_view_type', $default_view_type);
        $affectationProjet_viewType = $this->viewState->get('affectationProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('affectationProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.affectationProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.affectationProjet.visible");
        }
        
        // Récupération des données
        $affectationProjets_data = $this->paginate($params);
        $affectationProjets_stats = $this->getaffectationProjetStats();
        $affectationProjets_filters = $this->getFieldsFilterable();
        $affectationProjet_instance = $this->createInstance();
        $affectationProjet_viewTypes = $this->getViewTypes();
        $affectationProjet_partialViewName = $this->getPartialViewName($affectationProjet_viewType);
        $affectationProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.affectationProjet.stats', $affectationProjets_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'affectationProjet_viewTypes',
            'affectationProjet_viewType',
            'affectationProjets_data',
            'affectationProjets_stats',
            'affectationProjets_filters',
            'affectationProjet_instance',
            'affectationProjet_title',
            'contextKey'
        );
    
        return [
            'affectationProjets_data' => $affectationProjets_data,
            'affectationProjets_stats' => $affectationProjets_stats,
            'affectationProjets_filters' => $affectationProjets_filters,
            'affectationProjet_instance' => $affectationProjet_instance,
            'affectationProjet_viewType' => $affectationProjet_viewType,
            'affectationProjet_viewTypes' => $affectationProjet_viewTypes,
            'affectationProjet_partialViewName' => $affectationProjet_partialViewName,
            'contextKey' => $contextKey,
            'affectationProjet_compact_value' => $compact_value
        ];
    }

}
