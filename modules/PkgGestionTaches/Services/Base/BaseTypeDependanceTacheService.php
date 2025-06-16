<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgGestionTaches\Models\TypeDependanceTache;
use Modules\Core\Services\BaseService;

/**
 * Classe TypeDependanceTacheService pour gérer la persistance de l'entité TypeDependanceTache.
 */
class BaseTypeDependanceTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour typeDependanceTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'titre',
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
     * Constructeur de la classe TypeDependanceTacheService.
     */
    public function __construct()
    {
        parent::__construct(new TypeDependanceTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGestionTaches::typeDependanceTache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('typeDependanceTache');
        $this->fieldsFilterable = [];
    

    }

    /**
     * Crée une nouvelle instance de typeDependanceTache.
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
    public function getTypeDependanceTacheStats(): array
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
            'table' => 'PkgGestionTaches::typeDependanceTache._table',
            default => 'PkgGestionTaches::typeDependanceTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('typeDependanceTache_view_type', $default_view_type);
        $typeDependanceTache_viewType = $this->viewState->get('typeDependanceTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('typeDependanceTache_view_type') === 'widgets') {
            $this->viewState->set("scope.typeDependanceTache.visible", 1);
        }else{
            $this->viewState->remove("scope.typeDependanceTache.visible");
        }
        
        // Récupération des données
        $typeDependanceTaches_data = $this->paginate($params);
        $typeDependanceTaches_stats = $this->gettypeDependanceTacheStats();
        $typeDependanceTaches_filters = $this->getFieldsFilterable();
        $typeDependanceTache_instance = $this->createInstance();
        $typeDependanceTache_viewTypes = $this->getViewTypes();
        $typeDependanceTache_partialViewName = $this->getPartialViewName($typeDependanceTache_viewType);
        $typeDependanceTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.typeDependanceTache.stats', $typeDependanceTaches_stats);
    
        $typeDependanceTaches_permissions = [

            'edit-typeDependanceTache' => Auth::user()->can('edit-typeDependanceTache'),
            'destroy-typeDependanceTache' => Auth::user()->can('destroy-typeDependanceTache'),
            'show-typeDependanceTache' => Auth::user()->can('show-typeDependanceTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $typeDependanceTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($typeDependanceTaches_data as $item) {
                $typeDependanceTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'typeDependanceTache_viewTypes',
            'typeDependanceTache_viewType',
            'typeDependanceTaches_data',
            'typeDependanceTaches_stats',
            'typeDependanceTaches_filters',
            'typeDependanceTache_instance',
            'typeDependanceTache_title',
            'contextKey',
            'typeDependanceTaches_permissions',
            'typeDependanceTaches_permissionsByItem'
        );
    
        return [
            'typeDependanceTaches_data' => $typeDependanceTaches_data,
            'typeDependanceTaches_stats' => $typeDependanceTaches_stats,
            'typeDependanceTaches_filters' => $typeDependanceTaches_filters,
            'typeDependanceTache_instance' => $typeDependanceTache_instance,
            'typeDependanceTache_viewType' => $typeDependanceTache_viewType,
            'typeDependanceTache_viewTypes' => $typeDependanceTache_viewTypes,
            'typeDependanceTache_partialViewName' => $typeDependanceTache_partialViewName,
            'contextKey' => $contextKey,
            'typeDependanceTache_compact_value' => $compact_value,
            'typeDependanceTaches_permissions' => $typeDependanceTaches_permissions,
            'typeDependanceTaches_permissionsByItem' => $typeDependanceTaches_permissionsByItem
        ];
    }

}
