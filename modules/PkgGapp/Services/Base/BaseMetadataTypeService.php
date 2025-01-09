<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\MetadataType;
use Modules\Core\Services\BaseService;

/**
 * Classe MetadataTypeService pour gérer la persistance de l'entité MetadataType.
 */
class BaseMetadataTypeService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour metadataTypes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'code',
        'type',
        'scope',
        'description',
        'default_value',
        'validation_rules'
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
     * Constructeur de la classe MetadataTypeService.
     */
    public function __construct()
    {
        parent::__construct(new MetadataType());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de metadataType.
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
    public function getMetadataTypeStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
