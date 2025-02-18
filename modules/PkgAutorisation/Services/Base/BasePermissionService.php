<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\Services\Base;

use Modules\PkgAutorisation\Models\Permission;
use Modules\Core\Services\BaseService;

/**
 * Classe PermissionService pour gérer la persistance de l'entité Permission.
 */
class BasePermissionService extends BaseService
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
        $this->fieldsFilterable = [];
    }

    public function initFieldsFilterable(){
       // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("Core::sysController.plural"), 'controller_id', \Modules\Core\Models\SysController::class, 'name'),
        ];
    }

    /**
     * Crée une nouvelle instance de permission.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array|object $data)
    {
        return parent::create($data);
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getPermissionStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
