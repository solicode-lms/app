<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\NiveauCompetence;
use Modules\Core\Services\BaseService;

/**
 * Classe NiveauCompetenceService pour gérer la persistance de l'entité NiveauCompetence.
 */
class BaseNiveauCompetenceService extends BaseService
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

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgCompetences::competence.plural"), 'competence_id', \Modules\PkgCompetences\Models\Competence::class, 'code'),
        ];

    }

    /**
     * Crée une nouvelle instance de niveauCompetence.
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
    public function getNiveauCompetenceStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }

}
