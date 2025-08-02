<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgSessions\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgSessions\Models\LivrableSession;
use Modules\Core\Services\BaseService;

/**
 * Classe LivrableSessionService pour gérer la persistance de l'entité LivrableSession.
 */
class BaseLivrableSessionService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour livrableSessions.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'titre',
        'description',
        'session_formation_id',
        'nature_livrable_id'
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
     * Constructeur de la classe LivrableSessionService.
     */
    public function __construct()
    {
        parent::__construct(new LivrableSession());
        $this->fieldsFilterable = [];
        $this->title = __('PkgSessions::livrableSession.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('livrableSession');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('session_formation_id', $scopeVariables)) {


                    $sessionFormationService = new \Modules\PkgSessions\Services\SessionFormationService();
                    $sessionFormationIds = $this->getAvailableFilterValues('session_formation_id');
                    $sessionFormations = $sessionFormationService->getByIds($sessionFormationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgSessions::sessionFormation.plural"), 
                        'session_formation_id', 
                        \Modules\PkgSessions\Models\SessionFormation::class, 
                        'titre',
                        $sessionFormations
                    );
                }
            
            
                if (!array_key_exists('nature_livrable_id', $scopeVariables)) {


                    $natureLivrableService = new \Modules\PkgCreationProjet\Services\NatureLivrableService();
                    $natureLivrableIds = $this->getAvailableFilterValues('nature_livrable_id');
                    $natureLivrables = $natureLivrableService->getByIds($natureLivrableIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationProjet::natureLivrable.plural"), 
                        'nature_livrable_id', 
                        \Modules\PkgCreationProjet\Models\NatureLivrable::class, 
                        'nom',
                        $natureLivrables
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de livrableSession.
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
    public function getLivrableSessionStats(): array
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
            'table' => 'PkgSessions::livrableSession._table',
            default => 'PkgSessions::livrableSession._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('livrableSession_view_type', $default_view_type);
        $livrableSession_viewType = $this->viewState->get('livrableSession_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('livrableSession_view_type') === 'widgets') {
            $this->viewState->set("scope.livrableSession.visible", 1);
        }else{
            $this->viewState->remove("scope.livrableSession.visible");
        }
        
        // Récupération des données
        $livrableSessions_data = $this->paginate($params);
        $livrableSessions_stats = $this->getlivrableSessionStats();
        $livrableSessions_filters = $this->getFieldsFilterable();
        $livrableSession_instance = $this->createInstance();
        $livrableSession_viewTypes = $this->getViewTypes();
        $livrableSession_partialViewName = $this->getPartialViewName($livrableSession_viewType);
        $livrableSession_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.livrableSession.stats', $livrableSessions_stats);
    
        $livrableSessions_permissions = [

            'edit-livrableSession' => Auth::user()->can('edit-livrableSession'),
            'destroy-livrableSession' => Auth::user()->can('destroy-livrableSession'),
            'show-livrableSession' => Auth::user()->can('show-livrableSession'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $livrableSessions_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($livrableSessions_data as $item) {
                $livrableSessions_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'livrableSession_viewTypes',
            'livrableSession_viewType',
            'livrableSessions_data',
            'livrableSessions_stats',
            'livrableSessions_filters',
            'livrableSession_instance',
            'livrableSession_title',
            'contextKey',
            'livrableSessions_permissions',
            'livrableSessions_permissionsByItem'
        );
    
        return [
            'livrableSessions_data' => $livrableSessions_data,
            'livrableSessions_stats' => $livrableSessions_stats,
            'livrableSessions_filters' => $livrableSessions_filters,
            'livrableSession_instance' => $livrableSession_instance,
            'livrableSession_viewType' => $livrableSession_viewType,
            'livrableSession_viewTypes' => $livrableSession_viewTypes,
            'livrableSession_partialViewName' => $livrableSession_partialViewName,
            'contextKey' => $contextKey,
            'livrableSession_compact_value' => $compact_value,
            'livrableSessions_permissions' => $livrableSessions_permissions,
            'livrableSessions_permissionsByItem' => $livrableSessions_permissionsByItem
        ];
    }

}
