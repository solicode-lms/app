<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\HistoriqueRealisationTache;
use Modules\Core\Services\BaseService;

/**
 * Classe HistoriqueRealisationTacheService pour gérer la persistance de l'entité HistoriqueRealisationTache.
 */
class BaseHistoriqueRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour historiqueRealisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'dateModification',
        'changement',
        'realisation_tache_id',
        'user_id',
        'isFeedback'
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
     * Constructeur de la classe HistoriqueRealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new HistoriqueRealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGestionTaches::historiqueRealisationTache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('historiqueRealisationTache');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('realisation_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::realisationTache.plural"), 'realisation_tache_id', \Modules\PkgGestionTaches\Models\RealisationTache::class, 'id');
        }

        if (!array_key_exists('user_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutorisation::user.plural"), 'user_id', \Modules\PkgAutorisation\Models\User::class, 'name');
        }

    }

    /**
     * Crée une nouvelle instance de historiqueRealisationTache.
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
    public function getHistoriqueRealisationTacheStats(): array
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
            'table' => 'PkgGestionTaches::historiqueRealisationTache._table',
            default => 'PkgGestionTaches::historiqueRealisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('historiqueRealisationTache_view_type', $default_view_type);
        $historiqueRealisationTache_viewType = $this->viewState->get('historiqueRealisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('historiqueRealisationTache_view_type') === 'widgets') {
            $this->viewState->set("scope.historiqueRealisationTache.visible", 1);
        }else{
            $this->viewState->remove("scope.historiqueRealisationTache.visible");
        }
        
        // Récupération des données
        $historiqueRealisationTaches_data = $this->paginate($params);
        $historiqueRealisationTaches_stats = $this->gethistoriqueRealisationTacheStats();
        $historiqueRealisationTaches_filters = $this->getFieldsFilterable();
        $historiqueRealisationTache_instance = $this->createInstance();
        $historiqueRealisationTache_viewTypes = $this->getViewTypes();
        $historiqueRealisationTache_partialViewName = $this->getPartialViewName($historiqueRealisationTache_viewType);
        $historiqueRealisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.historiqueRealisationTache.stats', $historiqueRealisationTaches_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'historiqueRealisationTache_viewTypes',
            'historiqueRealisationTache_viewType',
            'historiqueRealisationTaches_data',
            'historiqueRealisationTaches_stats',
            'historiqueRealisationTaches_filters',
            'historiqueRealisationTache_instance',
            'historiqueRealisationTache_title',
            'contextKey'
        );
    
        return [
            'historiqueRealisationTaches_data' => $historiqueRealisationTaches_data,
            'historiqueRealisationTaches_stats' => $historiqueRealisationTaches_stats,
            'historiqueRealisationTaches_filters' => $historiqueRealisationTaches_filters,
            'historiqueRealisationTache_instance' => $historiqueRealisationTache_instance,
            'historiqueRealisationTache_viewType' => $historiqueRealisationTache_viewType,
            'historiqueRealisationTache_viewTypes' => $historiqueRealisationTache_viewTypes,
            'historiqueRealisationTache_partialViewName' => $historiqueRealisationTache_partialViewName,
            'contextKey' => $contextKey,
            'historiqueRealisationTache_compact_value' => $compact_value
        ];
    }

}
