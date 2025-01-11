<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Modules\PkgCreationProjet\Models\Projet;
use Modules\Core\Services\BaseService;

/**
 * Classe ProjetService pour gérer la persistance de l'entité Projet.
 */
class BaseProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour projets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'critere_de_travail',
        'date_debut',
        'date_fin',
        'description',
        'formateur_id',
        'titre',
        'travail_a_faire'
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
     * Constructeur de la classe ProjetService.
     */
    public function __construct()
    {
        parent::__construct(new Projet());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de projet.
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
    public function getProjetStats(): array
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
