<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\CategoryTechnology;
use Modules\Core\Services\BaseService;

/**
 * Classe CategoryTechnologyService pour gérer la persistance de l'entité CategoryTechnology.
 */
class BaseCategoryTechnologyService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour categoryTechnologies.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
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
     * Constructeur de la classe CategoryTechnologyService.
     */
    public function __construct()
    {
        parent::__construct(new CategoryTechnology());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::categoryTechnology.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('categoryTechnology');
        $this->fieldsFilterable = [];
    

    }

    /**
     * Crée une nouvelle instance de categoryTechnology.
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
    public function getCategoryTechnologyStats(): array
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
            'table' => 'PkgCompetences::categoryTechnology._table',
            default => 'PkgCompetences::categoryTechnology._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('categoryTechnology_view_type', $default_view_type);
        $categoryTechnology_viewType = $this->viewState->get('categoryTechnology_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('categoryTechnology_view_type') === 'widgets') {
            $this->viewState->set("scope.categoryTechnology.visible", 1);
        }else{
            $this->viewState->remove("scope.categoryTechnology.visible");
        }
        
        // Récupération des données
        $categoryTechnologies_data = $this->paginate($params);
        $categoryTechnologies_stats = $this->getcategoryTechnologyStats();
        $categoryTechnologies_filters = $this->getFieldsFilterable();
        $categoryTechnology_instance = $this->createInstance();
        $categoryTechnology_viewTypes = $this->getViewTypes();
        $categoryTechnology_partialViewName = $this->getPartialViewName($categoryTechnology_viewType);
        $categoryTechnology_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.categoryTechnology.stats', $categoryTechnologies_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'categoryTechnology_viewTypes',
            'categoryTechnology_viewType',
            'categoryTechnologies_data',
            'categoryTechnologies_stats',
            'categoryTechnologies_filters',
            'categoryTechnology_instance',
            'categoryTechnology_title',
            'contextKey'
        );
    
        return [
            'categoryTechnologies_data' => $categoryTechnologies_data,
            'categoryTechnologies_stats' => $categoryTechnologies_stats,
            'categoryTechnologies_filters' => $categoryTechnologies_filters,
            'categoryTechnology_instance' => $categoryTechnology_instance,
            'categoryTechnology_viewType' => $categoryTechnology_viewType,
            'categoryTechnology_viewTypes' => $categoryTechnology_viewTypes,
            'categoryTechnology_partialViewName' => $categoryTechnology_partialViewName,
            'contextKey' => $contextKey,
            'categoryTechnology_compact_value' => $compact_value
        ];
    }

}
