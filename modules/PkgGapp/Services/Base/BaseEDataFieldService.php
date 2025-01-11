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
        'type',
        'e_model_id',
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

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de eDataField.
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
    public function getEDataFieldStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
