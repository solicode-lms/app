<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\Services\Base;

use Modules\PkgAutorisation\Models\Role;
use Modules\Core\Services\BaseService;

/**
 * Classe RoleService pour gérer la persistance de l'entité Role.
 */
class BaseRoleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour roles.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'guard_name'
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
     * Constructeur de la classe RoleService.
     */
    public function __construct()
    {
        parent::__construct(new Role());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            ['field' => 'name', 'type' => 'String'],
            ['field' => 'Permission_ManyToMany', 'type' => 'ManyToMany'],
            ['field' => 'User_ManyToMany', 'type' => 'ManyToMany'],
        ];

    }

    /**
     * Crée une nouvelle instance de role.
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
    public function getRoleStats(): array
    {

        $stats = [];

        return $stats;
    }
}
