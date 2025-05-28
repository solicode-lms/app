<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

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


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('userModelFilter');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('user_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutorisation::user.plural"), 'user_id', \Modules\PkgAutorisation\Models\User::class, 'name');
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
        $userModelFilters_filters = $this->getFieldsFilterable();
        $userModelFilter_instance = $this->createInstance();
        $userModelFilter_viewTypes = $this->getViewTypes();
        $userModelFilter_partialViewName = $this->getPartialViewName($userModelFilter_viewType);
        $userModelFilter_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.userModelFilter.stats', $userModelFilters_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'userModelFilter_viewTypes',
            'userModelFilter_viewType',
            'userModelFilters_data',
            'userModelFilters_stats',
            'userModelFilters_filters',
            'userModelFilter_instance',
            'userModelFilter_title',
            'contextKey'
        );
    
        return [
            'userModelFilters_data' => $userModelFilters_data,
            'userModelFilters_stats' => $userModelFilters_stats,
            'userModelFilters_filters' => $userModelFilters_filters,
            'userModelFilter_instance' => $userModelFilter_instance,
            'userModelFilter_viewType' => $userModelFilter_viewType,
            'userModelFilter_viewTypes' => $userModelFilter_viewTypes,
            'userModelFilter_partialViewName' => $userModelFilter_partialViewName,
            'contextKey' => $contextKey,
            'userModelFilter_compact_value' => $compact_value
        ];
    }

}
