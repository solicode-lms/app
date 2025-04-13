<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\Technology;
use Modules\Core\Services\BaseService;

/**
 * Classe TechnologyService pour gérer la persistance de l'entité Technology.
 */
class BaseTechnologyService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour technologies.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'category_technology_id',
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
     * Constructeur de la classe TechnologyService.
     */
    public function __construct()
    {
        parent::__construct(new Technology());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::technology.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('technology');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('category_technology_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCompetences::categoryTechnology.plural"), 'category_technology_id', \Modules\PkgCompetences\Models\CategoryTechnology::class, 'nom');
        }


    }

    /**
     * Crée une nouvelle instance de technology.
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
    public function getTechnologyStats(): array
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
            'table' => 'PkgCompetences::technology._table',
            default => 'PkgCompetences::technology._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('technology_view_type', $default_view_type);
        $technology_viewType = $this->viewState->get('technology_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('technology_view_type') === 'widgets') {
            $this->viewState->set("filter.technology.visible", 1);
        }
        
        // Récupération des données
        $technologies_data = $this->paginate($params);
        $technologies_stats = $this->gettechnologyStats();
        $technologies_filters = $this->getFieldsFilterable();
        $technology_instance = $this->createInstance();
        $technology_viewTypes = $this->getViewTypes();
        $technology_partialViewName = $this->getPartialViewName($technology_viewType);
        $technology_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.technology.stats', $technologies_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'technology_viewTypes',
            'technology_viewType',
            'technologies_data',
            'technologies_stats',
            'technologies_filters',
            'technology_instance',
            'technology_title',
            'contextKey'
        );
    
        return [
            'technologies_data' => $technologies_data,
            'technologies_stats' => $technologies_stats,
            'technologies_filters' => $technologies_filters,
            'technology_instance' => $technology_instance,
            'technology_viewType' => $technology_viewType,
            'technology_viewTypes' => $technology_viewTypes,
            'technology_partialViewName' => $technology_partialViewName,
            'contextKey' => $contextKey,
            'technology_compact_value' => $compact_value
        ];
    }

}
