<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Modules\Core\Models\SysModule;
use Modules\Core\Services\BaseService;

/**
 * Classe SysModuleService pour gérer la persistance de l'entité SysModule.
 */
class BaseSysModuleService extends BaseService
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
        'version',
        'sys_color_id'
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
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sysModule');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
        }
    }

    /**
     * Crée une nouvelle instance de sysModule.
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
    public function getSysModuleStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
