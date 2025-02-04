<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\Technology;
use Modules\Core\Services\BaseService;

/**
 * Classe TechnologyService pour gérer la persistance de l'entité Technology.
 */
class BaseTechnologyService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour technologies.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'category_technology_id',
        'description'
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
     * Constructeur de la classe TechnologyService.
     */
    public function __construct()
    {
        parent::__construct(new Technology());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgCompetences::categoryTechnology.plural"), 'category_technology_id', \Modules\PkgCompetences\Models\CategoryTechnology::class, 'nom'),
        ];

    }

    /**
     * Crée une nouvelle instance de technology.
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
    public function getTechnologyStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }

}
