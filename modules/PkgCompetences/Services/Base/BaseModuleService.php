<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\Module;
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
        'description',
        'filiere_id',
        'masse_horaire',
        'nom'
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

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
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
        $module = parent::create([
            'description' => $data['description'],
            'filiere_id' => $data['filiere_id'],
            'masse_horaire' => $data['masse_horaire'],
            'nom' => $data['nom'],
        ]);

        return $module;
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getModuleStats(): array
    {

        $stats = [];

        
            $relationStatFiliere = parent::getStatsByRelation(
                \Modules\PkgCompetences\Models\Filiere::class,
                'modules',
                'code'
            );
            $stats = array_merge($stats, $relationStatFiliere);

        return $stats;
    }

}
