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
        'description',
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
            'description' => $data['description'],
            'nom' => $data['nom'],
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

        

        return $stats;
    }

}
