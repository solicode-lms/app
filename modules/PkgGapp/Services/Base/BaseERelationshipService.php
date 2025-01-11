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
     * Constructeur de la classe ERelationshipService.
     */
    public function __construct()
    {
        parent::__construct(new ERelationship());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de eRelationship.
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
    public function getERelationshipStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
