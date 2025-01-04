<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\Competence;
use Modules\Core\Services\BaseService;

/**
 * Classe CompetenceService pour gérer la persistance de l'entité Competence.
 */
class BaseCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour competences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'nom',
        'description',
        'module_id'
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
     * Constructeur de la classe CompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new Competence());
    }

    /**
     * Crée une nouvelle instance de competence.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $competence = parent::create([
            'code' => $data['code'],
            'nom' => $data['nom'],
            'description' => $data['description'],
            'module_id' => $data['module_id'],
        ]);

        return $competence;
    }

    
/**
 * Obtenir les statistiques des compétences par filière, incluant le total.
 *
 * @return array
 */
public function getCompetenceStats(): array
{
    // Récupérer toutes les filières avec leurs modules et compétences
    $filieres = \Modules\PkgCompetences\Models\Filiere::with('modules.competences')->get();

    // Initialiser les statistiques avec le total global
    $stats = [
        [
            'icon' => 'fas fa-box',
            'label' => 'Total des compétences',
            'value' => \Modules\PkgCompetences\Models\Competence::count(),
        ],
    ];

    // Parcourir chaque filière pour calculer les compétences par filière
    foreach ($filieres as $filiere) {
        $competencesCount = $filiere->modules->sum(function ($module) {
            return $module->competences->count();
        });

        $stats[] = [
            'icon' => 'fas fa-chart-pie',
            'label' => $filiere->code,
            'value' => $competencesCount,
        ];
    }

    return $stats;
}


}
