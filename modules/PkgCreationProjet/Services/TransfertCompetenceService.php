<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services;

use Modules\PkgCreationProjet\Models\TransfertCompetence;
use Modules\Core\Services\BaseService;

/**
 * Classe TransfertCompetenceService pour gérer la persistance de l'entité TransfertCompetence.
 */
class TransfertCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour transfertCompetences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'description',
        'projet_id',
        'competence_id',
        'appreciation_id'
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
     * Constructeur de la classe TransfertCompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new TransfertCompetence());
    }

    /**
     * Crée une nouvelle instance de transfertCompetence.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
