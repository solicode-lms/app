<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Models\UserModelFilter;
use Modules\Core\Services\BaseService;

/**
 * Classe UserModelFilterService pour gérer la persistance de l'entité UserModelFilter.
 */
class BaseUserModelFilterService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour userModelFilters.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'user_id',
        'model_name',
        'context_key',
        'filters'
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
     * Constructeur de la classe UserModelFilterService.
     */
    public function __construct()
    {
        parent::__construct(new UserModelFilter());
        $this->fieldsFilterable = [];
        $this->title = __('Core::userModelFilter.plural');
    }


    public function dataCalcul($userModelFilter)
    {
        // En Cas d'édit
        if(isset($userModelFilter->id)){
          
        }
      
        return $userModelFilter;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('userModelFilter');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('user_id', $scopeVariables)) {


                    $userService = new \Modules\PkgAutorisation\Services\UserService();
                    $userIds = $this->getAvailableFilterValues('user_id');
                    $users = $userService->getByIds($userIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgAutorisation::user.plural"), 
                        'user_id', 
                        \Modules\PkgAutorisation\Models\User::class, 
                        'name',
                        $users
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de userModelFilter.
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
    public function getUserModelFilterStats(): array
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
            'table' => 'Core::userModelFilter._table',
            default => 'Core::userModelFilter._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('userModelFilter_view_type', $default_view_type);
        $userModelFilter_viewType = $this->viewState->get('userModelFilter_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('userModelFilter_view_type') === 'widgets') {
            $this->viewState->set("scope.userModelFilter.visible", 1);
        }else{
            $this->viewState->remove("scope.userModelFilter.visible");
        }
        
        // Récupération des données
        $userModelFilters_data = $this->paginate($params);
        $userModelFilters_stats = $this->getuserModelFilterStats();
        $userModelFilters_total = $this->count();
        $userModelFilters_filters = $this->getFieldsFilterable();
        $userModelFilter_instance = $this->createInstance();
        $userModelFilter_viewTypes = $this->getViewTypes();
        $userModelFilter_partialViewName = $this->getPartialViewName($userModelFilter_viewType);
        $userModelFilter_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.userModelFilter.stats', $userModelFilters_stats);
    
        $userModelFilters_permissions = [

            'edit-userModelFilter' => Auth::user()->can('edit-userModelFilter'),
            'destroy-userModelFilter' => Auth::user()->can('destroy-userModelFilter'),
            'show-userModelFilter' => Auth::user()->can('show-userModelFilter'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $userModelFilters_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($userModelFilters_data as $item) {
                $userModelFilters_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'userModelFilter_viewTypes',
            'userModelFilter_viewType',
            'userModelFilters_data',
            'userModelFilters_stats',
            'userModelFilters_total',
            'userModelFilters_filters',
            'userModelFilter_instance',
            'userModelFilter_title',
            'contextKey',
            'userModelFilters_permissions',
            'userModelFilters_permissionsByItem'
        );
    
        return [
            'userModelFilters_data' => $userModelFilters_data,
            'userModelFilters_stats' => $userModelFilters_stats,
            'userModelFilters_total' => $userModelFilters_total,
            'userModelFilters_filters' => $userModelFilters_filters,
            'userModelFilter_instance' => $userModelFilter_instance,
            'userModelFilter_viewType' => $userModelFilter_viewType,
            'userModelFilter_viewTypes' => $userModelFilter_viewTypes,
            'userModelFilter_partialViewName' => $userModelFilter_partialViewName,
            'contextKey' => $contextKey,
            'userModelFilter_compact_value' => $compact_value,
            'userModelFilters_permissions' => $userModelFilters_permissions,
            'userModelFilters_permissionsByItem' => $userModelFilters_permissionsByItem
        ];
    }

}
