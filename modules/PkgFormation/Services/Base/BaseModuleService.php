<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Modules\PkgFormation\Models\Module;
use Modules\Core\Services\BaseService;

/**
 * Classe ModuleService pour gérer la persistance de l'entité Module.
 */
class BaseModuleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour modules.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'description',
        'masse_horaire',
        'filiere_id'
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
     * Constructeur de la classe ModuleService.
     */
    public function __construct()
    {
        parent::__construct(new Module());
        $this->fieldsFilterable = [];
    }

    public function initFieldsFilterable(){
       // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgFormation::filiere.plural"), 'filiere_id', \Modules\PkgFormation\Models\Filiere::class, 'code'),
        ];
    }

    /**
     * Crée une nouvelle instance de module.
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
    public function getModuleStats(): array
    {

        $stats = $this->initStats();

        
            $relationStatFiliere = parent::getStatsByRelation(
                \Modules\PkgFormation\Models\Filiere::class,
                'modules',
                'code'
            );
            $stats = array_merge($stats, $relationStatFiliere);

        return $stats;
    }

}
