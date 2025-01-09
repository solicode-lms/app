<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\Relationship;
use Modules\Core\Services\BaseService;

/**
 * Classe RelationshipService pour gérer la persistance de l'entité Relationship.
 */
class BaseRelationshipService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour relationships.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'source_model_id',
        'target_model_id',
        'type',
        'source_field',
        'target_field',
        'cascade_on_delete',
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
     * Constructeur de la classe RelationshipService.
     */
    public function __construct()
    {
        parent::__construct(new Relationship());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de relationship.
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
    public function getRelationshipStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
