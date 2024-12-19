<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgBlog\Services;

use Modules\PkgBlog\Models\User;
use Modules\Core\Services\BaseService;

/**
 * Classe UserService pour gérer la persistance de l'entité User.
 */
class UserService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour users.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token'
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
     * Constructeur de la classe UserService.
     */
    public function __construct()
    {
        parent::__construct(new User());
    }

    /**
     * Crée une nouvelle instance de user.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
