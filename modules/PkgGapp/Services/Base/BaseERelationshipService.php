<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\ERelationship;
use Modules\Core\Services\BaseService;

/**
 * Classe ERelationshipService pour gérer la persistance de l'entité ERelationship.
 */
class BaseERelationshipService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour eRelationships.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'type',
        'source_e_model_id',
        'target_e_model_id',
        'cascade_on_delete',
        'is_cascade',
        'description',
        'column_name',
        'referenced_table',
        'referenced_column',
        'through',
        'with_column',
        'morph_name'
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
     * Constructeur de la classe ERelationshipService.
     */
    public function __construct()
    {
        parent::__construct(new ERelationship());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGapp::eRelationship.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('eRelationship');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('type', $scopeVariables)) {
        $this->fieldsFilterable[] = ['field' => 'type', 'type' => 'String', 'label' => 'type'];
        }
        if (!array_key_exists('source_e_model_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGapp::eModel.plural"), 'source_e_model_id', \Modules\PkgGapp\Models\EModel::class, 'name');
        }
        if (!array_key_exists('target_e_model_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGapp::eModel.plural"), 'target_e_model_id', \Modules\PkgGapp\Models\EModel::class, 'name');
        }
    }

    /**
     * Crée une nouvelle instance de eRelationship.
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
    public function getERelationshipStats(): array
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
            'table' => 'PkgGapp::eRelationship._table',
            default => 'PkgGapp::eRelationship._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('eRelationship_view_type', $default_view_type);
        $eRelationship_viewType = $this->viewState->get('eRelationship_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('eRelationship_view_type') === 'widgets') {
            $this->viewState->set("filter.eRelationship.visible", 1);
        }
        
        // Récupération des données
        $eRelationships_data = $this->paginate($params);
        $eRelationships_stats = $this->geteRelationshipStats();
        $eRelationships_filters = $this->getFieldsFilterable();
        $eRelationship_instance = $this->createInstance();
        $eRelationship_viewTypes = $this->getViewTypes();
        $eRelationship_partialViewName = $this->getPartialViewName($eRelationship_viewType);
        $eRelationship_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.eRelationship.stats', $eRelationships_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'eRelationship_viewTypes',
            'eRelationship_viewType',
            'eRelationships_data',
            'eRelationships_stats',
            'eRelationships_filters',
            'eRelationship_instance',
            'eRelationship_title',
            'contextKey'
        );
    
        return [
            'eRelationships_data' => $eRelationships_data,
            'eRelationships_stats' => $eRelationships_stats,
            'eRelationships_filters' => $eRelationships_filters,
            'eRelationship_instance' => $eRelationship_instance,
            'eRelationship_viewType' => $eRelationship_viewType,
            'eRelationship_viewTypes' => $eRelationship_viewTypes,
            'eRelationship_partialViewName' => $eRelationship_partialViewName,
            'contextKey' => $contextKey,
            'eRelationship_compact_value' => $compact_value
        ];
    }

}
