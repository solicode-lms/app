<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgRealisationTache\Models\DependanceTache;
use Modules\Core\Services\BaseService;

/**
 * Classe DependanceTacheService pour gérer la persistance de l'entité DependanceTache.
 */
class BaseDependanceTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour dependanceTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'tache_id',
        'type_dependance_tache_id',
        'tache_cible_id'
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
     * Constructeur de la classe DependanceTacheService.
     */
    public function __construct()
    {
        parent::__construct(new DependanceTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationTache::dependanceTache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('dependanceTache');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCreationTache::tache.plural"), 'tache_id', \Modules\PkgCreationTache\Models\Tache::class, 'titre');
        }

        if (!array_key_exists('type_dependance_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgRealisationTache::typeDependanceTache.plural"), 'type_dependance_tache_id', \Modules\PkgRealisationTache\Models\TypeDependanceTache::class, 'titre');
        }

        if (!array_key_exists('tache_cible_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCreationTache::tache.plural"), 'tache_cible_id', \Modules\PkgCreationTache\Models\Tache::class, 'titre');
        }

    }

    /**
     * Crée une nouvelle instance de dependanceTache.
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
    public function getDependanceTacheStats(): array
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
            'table' => 'PkgRealisationTache::dependanceTache._table',
            default => 'PkgRealisationTache::dependanceTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('dependanceTache_view_type', $default_view_type);
        $dependanceTache_viewType = $this->viewState->get('dependanceTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('dependanceTache_view_type') === 'widgets') {
            $this->viewState->set("scope.dependanceTache.visible", 1);
        }else{
            $this->viewState->remove("scope.dependanceTache.visible");
        }
        
        // Récupération des données
        $dependanceTaches_data = $this->paginate($params);
        $dependanceTaches_stats = $this->getdependanceTacheStats();
        $dependanceTaches_filters = $this->getFieldsFilterable();
        $dependanceTache_instance = $this->createInstance();
        $dependanceTache_viewTypes = $this->getViewTypes();
        $dependanceTache_partialViewName = $this->getPartialViewName($dependanceTache_viewType);
        $dependanceTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.dependanceTache.stats', $dependanceTaches_stats);
    
        $dependanceTaches_permissions = [

            'edit-dependanceTache' => Auth::user()->can('edit-dependanceTache'),
            'destroy-dependanceTache' => Auth::user()->can('destroy-dependanceTache'),
            'show-dependanceTache' => Auth::user()->can('show-dependanceTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $dependanceTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($dependanceTaches_data as $item) {
                $dependanceTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'dependanceTache_viewTypes',
            'dependanceTache_viewType',
            'dependanceTaches_data',
            'dependanceTaches_stats',
            'dependanceTaches_filters',
            'dependanceTache_instance',
            'dependanceTache_title',
            'contextKey',
            'dependanceTaches_permissions',
            'dependanceTaches_permissionsByItem'
        );
    
        return [
            'dependanceTaches_data' => $dependanceTaches_data,
            'dependanceTaches_stats' => $dependanceTaches_stats,
            'dependanceTaches_filters' => $dependanceTaches_filters,
            'dependanceTache_instance' => $dependanceTache_instance,
            'dependanceTache_viewType' => $dependanceTache_viewType,
            'dependanceTache_viewTypes' => $dependanceTache_viewTypes,
            'dependanceTache_partialViewName' => $dependanceTache_partialViewName,
            'contextKey' => $contextKey,
            'dependanceTache_compact_value' => $compact_value,
            'dependanceTaches_permissions' => $dependanceTaches_permissions,
            'dependanceTaches_permissionsByItem' => $dependanceTaches_permissionsByItem
        ];
    }

}
