<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\Tache;
use Modules\Core\Services\BaseService;

/**
 * Classe TacheService pour gérer la persistance de l'entité Tache.
 */
class BaseTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour taches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'titre',
        'description',
        'dateDebut',
        'dateFin',
        'projet_id',
        'priorite_tache_id'
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
     * Constructeur de la classe TacheService.
     */
    public function __construct()
    {
        parent::__construct(new Tache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGestionTaches::tache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('tache');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCreationProjet::projet.plural"), 'projet_id', \Modules\PkgCreationProjet\Models\Projet::class, 'titre');
        }
        if (!array_key_exists('priorite_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::prioriteTache.plural"), 'priorite_tache_id', \Modules\PkgGestionTaches\Models\PrioriteTache::class, 'nom');
        }
    }

    /**
     * Crée une nouvelle instance de tache.
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
    public function getTacheStats(): array
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
            'table' => 'PkgGestionTaches::tache._table',
            default => 'PkgGestionTaches::tache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('tache_view_type', $default_view_type);
        $tache_viewType = $this->viewState->get('tache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('tache_view_type') === 'widgets') {
            $this->viewState->set("filter.tache.visible", 1);
        }
        
        // Récupération des données
        $taches_data = $this->paginate($params);
        $taches_stats = $this->gettacheStats();
        $taches_filters = $this->getFieldsFilterable();
        $tache_instance = $this->createInstance();
        $tache_viewTypes = $this->getViewTypes();
        $tache_partialViewName = $this->getPartialViewName($tache_viewType);
        $tache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.tache.stats', $taches_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'tache_viewTypes',
            'tache_viewType',
            'taches_data',
            'taches_stats',
            'taches_filters',
            'tache_instance',
            'tache_title',
            'contextKey'
        );
    
        return [
            'taches_data' => $taches_data,
            'taches_stats' => $taches_stats,
            'taches_filters' => $taches_filters,
            'tache_instance' => $tache_instance,
            'tache_viewType' => $tache_viewType,
            'tache_viewTypes' => $tache_viewTypes,
            'tache_partialViewName' => $tache_partialViewName,
            'contextKey' => $contextKey,
            'tache_compact_value' => $compact_value
        ];
    }

}
