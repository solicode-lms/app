<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\EMetadatum;
use Modules\Core\Services\BaseService;

/**
 * Classe EMetadatumService pour gérer la persistance de l'entité EMetadatum.
 */
class BaseEMetadatumService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour eMetadata.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'value_boolean',
        'value_string',
        'value_int',
        'value_object',
        'object_id',
        'object_type',
        'e_metadata_definition_id'
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
     * Constructeur de la classe EMetadatumService.
     */
    public function __construct()
    {
        parent::__construct(new EMetadatum());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de eMetadatum.
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
    public function getEMetadatumStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
