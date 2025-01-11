<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Modules\Core\Models\SysController;
use Modules\Core\Services\BaseService;

/**
 * Classe SysControllerService pour gérer la persistance de l'entité SysController.
 */
class BaseSysControllerService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sysControllers.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'module_id',
        'name',
        'slug',
        'description',
        'is_active'
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
     * Constructeur de la classe SysControllerService.
     */
    public function __construct()
    {
        parent::__construct(new SysController());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de sysController.
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
    public function getSysControllerStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
