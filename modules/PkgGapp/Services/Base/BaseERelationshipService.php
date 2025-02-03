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
        'name',
        'type',
        'source_e_model_id',
        'target_e_model_id',
        'cascade_on_delete',
        'is_cascade',
        'description',
        'column_name',
        'referenced_table',
        'referenced_column',
        'through',
        'with_column',
        'morph_name'
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
            $this->generateManyToOneFilter(__("PkgGapp::eModel.plural"), 'source_e_model_id', \Modules\PkgGapp\Models\EModel::class, 'name'),
            $this->generateManyToOneFilter(__("PkgGapp::eModel.plural"), 'target_e_model_id', \Modules\PkgGapp\Models\EModel::class, 'name'),
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

        $stats = $this->initStats();

        

        return $stats;
    }

}
