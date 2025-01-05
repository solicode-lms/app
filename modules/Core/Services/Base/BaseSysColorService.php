<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Modules\Core\Models\SysColor;
use Modules\Core\Services\BaseService;

/**
 * Classe SysColorService pour gérer la persistance de l'entité SysColor.
 */
class BaseSysColorService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sysColors.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'hex'
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
     * Constructeur de la classe SysColorService.
     */
    public function __construct()
    {
        parent::__construct(new SysColor());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            ['field' => 'name', 'type' => 'String'],
        ];

    }

    /**
     * Crée une nouvelle instance de sysColor.
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
    public function getSysColorStats(): array
    {

        $stats = [];

        return $stats;
    }
}
