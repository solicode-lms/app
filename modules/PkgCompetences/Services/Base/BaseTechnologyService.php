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
        'description',
        'category_technology_id'
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
        $technology = parent::create([
            'nom' => $data['nom'],
            'description' => $data['description'],
            'category_technology_id' => $data['category_technology_id'],
        ]);

        return $technology;
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getTechnologyStats(): array
    {

        $stats = [];

        // Ajouter les statistiques du propriétaire
        $contexteState = $this->getContextState();
        if ($contexteState !== null) {
            $stats[] = $contexteState;
        }


        return $stats;
    }

    public function getContextState()
    {
        if(!$this->contextState->isContextStateEnable()) return null; 
        $value = $this->contextState->getTitle();
        return [
                "icon" => "fas fa-filter",
                "label" => "Filtre",
                "value" =>  $value
        ];
    }
}
