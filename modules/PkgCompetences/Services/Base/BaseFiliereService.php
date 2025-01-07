<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\Filiere;
use Modules\Core\Services\BaseService;

/**
 * Classe FiliereService pour gérer la persistance de l'entité Filiere.
 */
class BaseFiliereService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour filieres.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
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
     * Constructeur de la classe FiliereService.
     */
    public function __construct()
    {
        parent::__construct(new Filiere());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de filiere.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $filiere = parent::create([
            'code' => $data['code'],
            'nom' => $data['nom'],
            'description' => $data['description'],
        ]);

        return $filiere;
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getFiliereStats(): array
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
