<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Modules\PkgAutoformation\Models\RealisationFormation;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationFormationService pour gérer la persistance de l'entité RealisationFormation.
 */
class BaseRealisationFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationFormations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'date_debut',
        'date_fin',
        'formation_id',
        'apprenant_id',
        'etat_formation_id'
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
     * Constructeur de la classe RealisationFormationService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationFormation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgAutoformation::realisationFormation.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationFormation');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('formation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::formation.plural"), 'formation_id', \Modules\PkgAutoformation\Models\Formation::class, 'nom');
        }
        if (!array_key_exists('apprenant_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgApprenants::apprenant.plural"), 'apprenant_id', \Modules\PkgApprenants\Models\Apprenant::class, 'nom');
        }
        if (!array_key_exists('etat_formation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::etatFormation.plural"), 'etat_formation_id', \Modules\PkgAutoformation\Models\EtatFormation::class, 'nom');
        }
    }

    /**
     * Crée une nouvelle instance de realisationFormation.
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
    public function getRealisationFormationStats(): array
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
            'table' => 'PkgAutoformation::realisationFormation._table',
            default => 'PkgAutoformation::realisationFormation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('realisationFormation_view_type', $default_view_type);
        $realisationFormation_viewType = $this->viewState->get('realisationFormation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationFormation_view_type') === 'widgets') {
            $this->viewState->set("filter.realisationFormation.visible", 1);
        }
        
        // Récupération des données
        $realisationFormations_data = $this->paginate($params);
        $realisationFormations_stats = $this->getrealisationFormationStats();
        $realisationFormations_filters = $this->getFieldsFilterable();
        $realisationFormation_instance = $this->createInstance();
        $realisationFormation_viewTypes = $this->getViewTypes();
        $realisationFormation_partialViewName = $this->getPartialViewName($realisationFormation_viewType);
        $realisationFormation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationFormation.stats', $realisationFormations_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationFormation_viewTypes',
            'realisationFormation_viewType',
            'realisationFormations_data',
            'realisationFormations_stats',
            'realisationFormations_filters',
            'realisationFormation_instance',
            'realisationFormation_title',
            'contextKey'
        );
    
        return [
            'realisationFormations_data' => $realisationFormations_data,
            'realisationFormations_stats' => $realisationFormations_stats,
            'realisationFormations_filters' => $realisationFormations_filters,
            'realisationFormation_instance' => $realisationFormation_instance,
            'realisationFormation_viewType' => $realisationFormation_viewType,
            'realisationFormation_viewTypes' => $realisationFormation_viewTypes,
            'realisationFormation_partialViewName' => $realisationFormation_partialViewName,
            'contextKey' => $contextKey,
            'realisationFormation_compact_value' => $compact_value
        ];
    }

}
