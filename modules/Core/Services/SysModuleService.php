<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services;

use Modules\Core\Models\SysModule;
use Modules\Core\Services\BaseService;

/**
 * Classe SysModuleService pour gérer la persistance de l'entité SysModule.
 */
class SysModuleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sysModules.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'slug',
        'description',
        'is_active',
        'order',
        'version'
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
     * Constructeur de la classe SysModuleService.
     */
    public function __construct()
    {
        parent::__construct(new SysModule());
    }

    /**
     * Crée une nouvelle instance de sysModule.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
