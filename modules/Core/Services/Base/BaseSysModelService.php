<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Modules\Core\Models\SysModel;
use Modules\Core\Services\BaseService;

/**
 * Classe SysModelService pour gérer la persistance de l'entité SysModel.
 */
class BaseSysModelService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sysModels.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'color_id',
        'description',
        'model',
        'module_id',
        'name'
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
     * Constructeur de la classe SysModelService.
     */
    public function __construct()
    {
        parent::__construct(new SysModel());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de sysModel.
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
    public function getSysModelStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
