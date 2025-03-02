<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\CategoryTechnology;
use Modules\Core\Services\BaseService;

/**
 * Classe CategoryTechnologyService pour gérer la persistance de l'entité CategoryTechnology.
 */
class BaseCategoryTechnologyService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour categoryTechnologies.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
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
     * Constructeur de la classe CategoryTechnologyService.
     */
    public function __construct()
    {
        parent::__construct(new CategoryTechnology());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('categoryTechnology');
        $this->fieldsFilterable = [];
    
    }

    /**
     * Crée une nouvelle instance de categoryTechnology.
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
    public function getCategoryTechnologyStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
