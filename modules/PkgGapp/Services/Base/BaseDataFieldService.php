<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\DataField;
use Modules\Core\Services\BaseService;

/**
 * Classe DataFieldService pour gérer la persistance de l'entité DataField.
 */
class BaseDataFieldService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour dataFields.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'i_model_id',
        'field_type_id',
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
     * Constructeur de la classe DataFieldService.
     */
    public function __construct()
    {
        parent::__construct(new DataField());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de dataField.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getDataFieldStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
