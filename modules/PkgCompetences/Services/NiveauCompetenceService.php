<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services;

use Modules\PkgCompetences\Models\NiveauCompetence;
use Modules\Core\Services\BaseService;

/**
 * Classe NiveauCompetenceService pour gérer la persistance de l'entité NiveauCompetence.
 */
class NiveauCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour niveauCompetences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'description',
        'competence_id'
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
     * Constructeur de la classe NiveauCompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new NiveauCompetence());
    }

    /**
     * Crée une nouvelle instance de niveauCompetence.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $niveauCompetence = parent::create([
            'nom' => $data['nom'],
            'description' => $data['description'],
            'competence_id' => $data['competence_id'],
        ]);

        return $niveauCompetence;
    }
}
