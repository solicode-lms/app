<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Modules\PkgCreationProjet\Models\TransfertCompetence;
use Modules\Core\Services\BaseService;

/**
 * Classe TransfertCompetenceService pour gérer la persistance de l'entité TransfertCompetence.
 */
class BaseTransfertCompetenceService extends BaseService
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

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter('projet_id', \Modules\PkgCreationProjet\Models\Projet::class, 'titre'),
            $this->generateManyToOneFilter('competence_id', \Modules\PkgCompetences\Models\Competence::class, 'code'),
            $this->generateManyToOneFilter('appreciation_id', \Modules\PkgCompetences\Models\Appreciation::class, 'nom'),
            ['field' => 'Technology_ManyToMany', 'type' => 'ManyToMany'],
        ];

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

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getTransfertCompetenceStats(): array
    {

        $stats = [];

        return $stats;
    }
}
