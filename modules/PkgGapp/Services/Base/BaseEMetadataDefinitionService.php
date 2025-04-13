<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\EMetadataDefinition;
use Modules\Core\Services\BaseService;

/**
 * Classe EMetadataDefinitionService pour gérer la persistance de l'entité EMetadataDefinition.
 */
class BaseEMetadataDefinitionService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour eMetadataDefinitions.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'groupe',
        'type',
        'scope',
        'description',
        'default_value'
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
     * Constructeur de la classe EMetadataDefinitionService.
     */
    public function __construct()
    {
        parent::__construct(new EMetadataDefinition());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGapp::eMetadataDefinition.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('eMetadataDefinition');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('groupe', $scopeVariables)) {
        $this->fieldsFilterable[] = ['field' => 'groupe', 'type' => 'String', 'label' => 'groupe'];
        }
    }

    /**
     * Crée une nouvelle instance de eMetadataDefinition.
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
    public function getEMetadataDefinitionStats(): array
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
            'table' => 'PkgGapp::eMetadataDefinition._table',
            default => 'PkgGapp::eMetadataDefinition._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('eMetadataDefinition_view_type', $default_view_type);
        $eMetadataDefinition_viewType = $this->viewState->get('eMetadataDefinition_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('eMetadataDefinition_view_type') === 'widgets') {
            $this->viewState->set("filter.eMetadataDefinition.visible", 1);
        }
        
        // Récupération des données
        $eMetadataDefinitions_data = $this->paginate($params);
        $eMetadataDefinitions_stats = $this->geteMetadataDefinitionStats();
        $eMetadataDefinitions_filters = $this->getFieldsFilterable();
        $eMetadataDefinition_instance = $this->createInstance();
        $eMetadataDefinition_viewTypes = $this->getViewTypes();
        $eMetadataDefinition_partialViewName = $this->getPartialViewName($eMetadataDefinition_viewType);
        $eMetadataDefinition_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.eMetadataDefinition.stats', $eMetadataDefinitions_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'eMetadataDefinition_viewTypes',
            'eMetadataDefinition_viewType',
            'eMetadataDefinitions_data',
            'eMetadataDefinitions_stats',
            'eMetadataDefinitions_filters',
            'eMetadataDefinition_instance',
            'eMetadataDefinition_title',
            'contextKey'
        );
    
        return [
            'eMetadataDefinitions_data' => $eMetadataDefinitions_data,
            'eMetadataDefinitions_stats' => $eMetadataDefinitions_stats,
            'eMetadataDefinitions_filters' => $eMetadataDefinitions_filters,
            'eMetadataDefinition_instance' => $eMetadataDefinition_instance,
            'eMetadataDefinition_viewType' => $eMetadataDefinition_viewType,
            'eMetadataDefinition_viewTypes' => $eMetadataDefinition_viewTypes,
            'eMetadataDefinition_partialViewName' => $eMetadataDefinition_partialViewName,
            'contextKey' => $contextKey,
            'eMetadataDefinition_compact_value' => $compact_value
        ];
    }

}
