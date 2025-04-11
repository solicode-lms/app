<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Modules\Core\Models\FeatureDomain;
use Modules\Core\Services\BaseService;

/**
 * Classe FeatureDomainService pour gérer la persistance de l'entité FeatureDomain.
 */
class BaseFeatureDomainService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour featureDomains.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'slug',
        'description',
        'sys_module_id'
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
     * Constructeur de la classe FeatureDomainService.
     */
    public function __construct()
    {
        parent::__construct(new FeatureDomain());
        $this->fieldsFilterable = [];
        $this->title = __('Core::featureDomain.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('featureDomain');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('sys_module_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysModule.plural"), 'sys_module_id', \Modules\Core\Models\SysModule::class, 'name');
        }
    }

    /**
     * Crée une nouvelle instance de featureDomain.
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
    public function getFeatureDomainStats(): array
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
            'table' => 'Core::featureDomain._table',
            default => 'Core::featureDomain._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('featureDomain_view_type', $default_view_type);
        $featureDomain_viewType = $this->viewState->get('featureDomain_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('featureDomain_view_type') === 'widgets') {
            $this->viewState->set("filter.featureDomain.visible", 1);
        }
        
        // Récupération des données
        $featureDomains_data = $this->paginate($params);
        $featureDomains_stats = $this->getfeatureDomainStats();
        $featureDomains_filters = $this->getFieldsFilterable();
        $featureDomain_instance = $this->createInstance();
        $featureDomain_viewTypes = $this->getViewTypes();
        $featureDomain_partialViewName = $this->getPartialViewName($featureDomain_viewType);
        $featureDomain_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.featureDomain.stats', $featureDomains_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'featureDomain_viewTypes',
            'featureDomain_viewType',
            'featureDomains_data',
            'featureDomains_stats',
            'featureDomains_filters',
            'featureDomain_instance',
            'featureDomain_title',
            'contextKey'
        );
    
        return [
            'featureDomains_data' => $featureDomains_data,
            'featureDomains_stats' => $featureDomains_stats,
            'featureDomains_filters' => $featureDomains_filters,
            'featureDomain_instance' => $featureDomain_instance,
            'featureDomain_viewType' => $featureDomain_viewType,
            'featureDomain_viewTypes' => $featureDomain_viewTypes,
            'featureDomain_partialViewName' => $featureDomain_partialViewName,
            'contextKey' => $contextKey,
            'featureDomain_compact_value' => $compact_value
        ];
    }

}
