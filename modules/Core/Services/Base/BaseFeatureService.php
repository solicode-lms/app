<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Modules\Core\Models\Feature;
use Modules\Core\Services\BaseService;

/**
 * Classe FeatureService pour gérer la persistance de l'entité Feature.
 */
class BaseFeatureService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour features.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'description',
        'feature_domain_id'
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
     * Constructeur de la classe FeatureService.
     */
    public function __construct()
    {
        parent::__construct(new Feature());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('feature');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('feature_domain_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::featureDomain.plural"), 'feature_domain_id', \Modules\Core\Models\FeatureDomain::class, 'name');
        }
    }

    /**
     * Crée une nouvelle instance de feature.
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
    public function getFeatureStats(): array
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
            'table' => 'Core::feature._table',
            default => 'Core::feature._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('feature_view_type', $default_view_type);
        $feature_viewType = $this->viewState->get('feature_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('feature_view_type') === 'widgets') {
            $this->viewState->set("filter.feature.visible", 1);
        }
        
        // Récupération des données
        $features_data = $this->paginate($params);
        $features_stats = $this->getfeatureStats();
        $features_filters = $this->getFieldsFilterable();
        $feature_instance = $this->createInstance();
        $feature_viewTypes = $this->getViewTypes();
        $feature_partialViewName = $this->getPartialViewName($feature_viewType);
    
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.feature.stats', $features_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'feature_viewTypes',
            'feature_viewType',
            'features_data',
            'features_stats',
            'features_filters',
            'feature_instance'
        );
    
        return [
            'features_data' => $features_data,
            'features_stats' => $features_stats,
            'features_filters' => $features_filters,
            'feature_instance' => $feature_instance,
            'feature_viewType' => $feature_viewType,
            'feature_viewTypes' => $feature_viewTypes,
            'feature_partialViewName' => $feature_partialViewName,
            'feature_compact_value' => $compact_value
        ];
    }

}
