<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Services;

use Modules\PkgUtilisateurs\Models\Niveaux_scolaire;
use Modules\Core\Services\BaseService;

/**
 * Classe Niveaux_scolaireService pour gérer la persistance de l'entité Niveaux_scolaire.
 */
class Niveaux_scolaireService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour niveaux_scolaires.
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
     * Constructeur de la classe Niveaux_scolaireService.
     */
    public function __construct()
    {
        parent::__construct(new Niveaux_scolaire());
    }

    /**
     * Crée une nouvelle instance de niveaux_scolaire.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $niveaux_scolaire = parent::create([
            'nom' => $data['nom'],
            'description' => $data['description'],
        ]);

        return $niveaux_scolaire;
    }
}
