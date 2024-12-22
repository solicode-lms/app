<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\Services;

use Modules\PkgAutorisation\Models\Permission;
use Modules\Core\Services\BaseService;

/**
 * Classe PermissionService pour gérer la persistance de l'entité Permission.
 */
class PermissionService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour permissions.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'guard_name',
        'controller_id'
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
     * Constructeur de la classe PermissionService.
     */
    public function __construct()
    {
        parent::__construct(new Permission());
    }

    /**
     * Crée une nouvelle instance de permission.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
