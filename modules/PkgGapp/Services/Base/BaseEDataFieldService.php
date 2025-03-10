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
        'data_type',
        'column_name',
        'e_model_id',
        'e_relationship_id',
        'field_order',
        'default_value',
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
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('eDataField');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('data_type', $scopeVariables)) {
        $this->fieldsFilterable[] = ['field' => 'data_type', 'type' => 'String', 'label' => 'data_type'];
        }
        if (!array_key_exists('e_model_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGapp::eModel.plural"), 'e_model_id', \Modules\PkgGapp\Models\EModel::class, 'name');
        }
        if (!array_key_exists('e_relationship_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGapp::eRelationship.plural"), 'e_relationship_id', \Modules\PkgGapp\Models\ERelationship::class, 'name');
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
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getEDataFieldStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
