<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\Metadatum;
use Modules\Core\Services\BaseService;

/**
 * Classe MetadatumService pour gérer la persistance de l'entité Metadatum.
 */
class BaseMetadatumService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour metadata.
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
        'metadata_type_id'
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
     * Constructeur de la classe MetadatumService.
     */
    public function __construct()
    {
        parent::__construct(new Metadatum());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de metadatum.
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
    public function getMetadatumStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
