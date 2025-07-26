<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgCreationTache\Models\Tache;
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
        'ordre',
        'titre',
        'priorite',
        'projet_id',
        'description',
        'dateDebut',
        'dateFin',
        'note',
        'phase_evaluation_id',
        'chapitre_id',
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
        $this->title = __('PkgCreationTache::tache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('tache');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('projet_id', $scopeVariables)) {
                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationProjet::projet.plural"), 
                        'projet_id', 
                        \Modules\PkgCreationProjet\Models\Projet::class, 
                        'titre'
                    );
                }
            
            
                if (!array_key_exists('phase_evaluation_id', $scopeVariables)) {
                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::phaseEvaluation.plural"), 
                        'phase_evaluation_id', 
                        \Modules\PkgCompetences\Models\PhaseEvaluation::class, 
                        'code'
                    );
                }
            
            
                if (!array_key_exists('chapitre_id', $scopeVariables)) {
                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::chapitre.plural"), 
                        'chapitre_id', 
                        \Modules\PkgCompetences\Models\Chapitre::class, 
                        'code'
                    );
                }
            
            
                if (!array_key_exists('priorite_tache_id', $scopeVariables)) {
                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationTache::prioriteTache.plural"), 
                        'priorite_tache_id', 
                        \Modules\PkgCreationTache\Models\PrioriteTache::class, 
                        'nom'
                    );
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
            'table' => 'PkgCreationTache::tache._table',
            default => 'PkgCreationTache::tache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('tache_view_type', $default_view_type);
        $tache_viewType = $this->viewState->get('tache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('tache_view_type') === 'widgets') {
            $this->viewState->set("scope.tache.visible", 1);
        }else{
            $this->viewState->remove("scope.tache.visible");
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
    
        $taches_permissions = [

            'edit-tache' => Auth::user()->can('edit-tache'),
            'destroy-tache' => Auth::user()->can('destroy-tache'),
            'show-tache' => Auth::user()->can('show-tache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $taches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($taches_data as $item) {
                $taches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'tache_viewTypes',
            'tache_viewType',
            'taches_data',
            'taches_stats',
            'taches_filters',
            'tache_instance',
            'tache_title',
            'contextKey',
            'taches_permissions',
            'taches_permissionsByItem'
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
            'tache_compact_value' => $compact_value,
            'taches_permissions' => $taches_permissions,
            'taches_permissionsByItem' => $taches_permissionsByItem
        ];
    }

}
