<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\EDataField;
use Modules\Core\Services\BaseService;

/**
 * Classe EDataFieldService pour gérer la persistance de l'entité EDataField.
 */
class BaseEDataFieldService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour eDataFields.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'default_value',
        'column_name',
        'e_model_id',
        'e_relationship_id',
        'field_order',
        'data_type',
        'db_primaryKey',
        'db_nullable',
        'db_unique',
        'calculable',
        'calculable_sql',
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
     * Constructeur de la classe EDataFieldService.
     */
    public function __construct()
    {
        parent::__construct(new EDataField());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGapp::eDataField.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('eDataField');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('e_model_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGapp::eModel.plural"), 'e_model_id', \Modules\PkgGapp\Models\EModel::class, 'name');
        }
        if (!array_key_exists('data_type', $scopeVariables)) {
        $this->fieldsFilterable[] = ['field' => 'data_type', 'type' => 'String', 'label' => 'data_type'];
        }
    }

    /**
     * Crée une nouvelle instance de eDataField.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array|object $data)
    {
        return parent::create($data);
    }

    /**
     * Trie par date de mise à jour si il n'existe aucune trie
     * @param mixed $query
     * @param mixed $sort
     */
    public function applySort($query, $sort)
    {
        if ($sort) {
            return parent::applySort($query, $sort);
        }else{
            return $query->orderBy("updated_at","desc");
        }
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getEDataFieldStats(): array
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
            'table' => 'PkgGapp::eDataField._table',
            default => 'PkgGapp::eDataField._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('eDataField_view_type', $default_view_type);
        $eDataField_viewType = $this->viewState->get('eDataField_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('eDataField_view_type') === 'widgets') {
            $this->viewState->set("filter.eDataField.visible", 1);
        }
        
        // Récupération des données
        $eDataFields_data = $this->paginate($params);
        $eDataFields_stats = $this->geteDataFieldStats();
        $eDataFields_filters = $this->getFieldsFilterable();
        $eDataField_instance = $this->createInstance();
        $eDataField_viewTypes = $this->getViewTypes();
        $eDataField_partialViewName = $this->getPartialViewName($eDataField_viewType);
        $eDataField_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.eDataField.stats', $eDataFields_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'eDataField_viewTypes',
            'eDataField_viewType',
            'eDataFields_data',
            'eDataFields_stats',
            'eDataFields_filters',
            'eDataField_instance',
            'eDataField_title',
            'contextKey'
        );
    
        return [
            'eDataFields_data' => $eDataFields_data,
            'eDataFields_stats' => $eDataFields_stats,
            'eDataFields_filters' => $eDataFields_filters,
            'eDataField_instance' => $eDataField_instance,
            'eDataField_viewType' => $eDataField_viewType,
            'eDataField_viewTypes' => $eDataField_viewTypes,
            'eDataField_partialViewName' => $eDataField_partialViewName,
            'contextKey' => $contextKey,
            'eDataField_compact_value' => $compact_value
        ];
    }

}
