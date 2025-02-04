<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\NiveauDifficulte;
use Modules\Core\Services\BaseService;

/**
 * Classe NiveauDifficulteService pour gérer la persistance de l'entité NiveauDifficulte.
 */
class BaseNiveauDifficulteService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour niveauDifficultes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'noteMin',
        'noteMax',
        'formateur_id',
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
     * Constructeur de la classe NiveauDifficulteService.
     */
    public function __construct()
    {
        parent::__construct(new NiveauDifficulte());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom'),
        ];

    }

    /**
     * Crée une nouvelle instance de niveauDifficulte.
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
    public function getNiveauDifficulteStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }

}
