<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

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
        $this->viewState->init('typeDependanceTache_view_type', $default_view_type);
        $typeDependanceTache_viewType = $this->viewState->get('typeDependanceTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('typeDependanceTache_view_type') === 'widgets') {
            $this->viewState->set("filter.typeDependanceTache.visible", 1);
        }
        
        // Récupération des données
        $typeDependanceTaches_data = $this->paginate($params);
        $typeDependanceTaches_stats = $this->gettypeDependanceTacheStats();
        $typeDependanceTaches_filters = $this->getFieldsFilterable();
        $typeDependanceTache_instance = $this->createInstance();
        $typeDependanceTache_viewTypes = $this->getViewTypes();
        $typeDependanceTache_partialViewName = $this->getPartialViewName($typeDependanceTache_viewType);
    
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.typeDependanceTache.stats', $typeDependanceTaches_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'typeDependanceTache_viewTypes',
            'typeDependanceTache_viewType',
            'typeDependanceTaches_data',
            'typeDependanceTaches_stats',
            'typeDependanceTaches_filters',
            'typeDependanceTache_instance'
        );
    
        return [
            'typeDependanceTaches_data' => $typeDependanceTaches_data,
            'typeDependanceTaches_stats' => $typeDependanceTaches_stats,
            'typeDependanceTaches_filters' => $typeDependanceTaches_filters,
            'typeDependanceTache_instance' => $typeDependanceTache_instance,
            'typeDependanceTache_viewType' => $typeDependanceTache_viewType,
            'typeDependanceTache_viewTypes' => $typeDependanceTache_viewTypes,
            'typeDependanceTache_partialViewName' => $typeDependanceTache_partialViewName,
            'typeDependanceTache_compact_value' => $compact_value
        ];
    }

}
