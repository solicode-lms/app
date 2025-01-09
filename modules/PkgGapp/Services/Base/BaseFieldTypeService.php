<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\FieldType;
use Modules\Core\Services\BaseService;

/**
 * Classe FieldTypeService pour gérer la persistance de l'entité FieldType.
 */
class BaseFieldTypeService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour fieldTypes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
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
     * Constructeur de la classe FieldTypeService.
     */
    public function __construct()
    {
        parent::__construct(new FieldType());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de fieldType.
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
    public function getFieldTypeStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
