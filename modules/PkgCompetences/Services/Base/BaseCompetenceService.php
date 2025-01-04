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
/**
 * Obtenir les statistiques des compétences par filière, incluant le total.
 *
 * @return array
 */
public function getCompetenceStats(): array
{
    // Calculer le total global des compétences
    $totalCompetences = $this->getNestedRelationAsCollection(
        \Modules\PkgCompetences\Models\Filiere::class,
        'modules.competences'
    )->count();

    // Récupérer toutes les filières
    $filieres = \Modules\PkgCompetences\Models\Module::all();

    // Initialiser les statistiques avec le total global
    $stats = [
        [
            'icon' => 'fas fa-box',
            'label' => 'Total des compétences',
            'value' => $totalCompetences,
        ],
    ];

    // Parcourir chaque filière pour calculer les compétences par filière
    foreach ($filieres as $filiere) {
        $competences = $this->getNestedRelationAsCollection(
            \Modules\PkgCompetences\Models\Module::class,
            'competences',
            $filiere->id // Passer l'ID de la filière pour filtrer
        );

        $stats[] = [
            'icon' => 'fas fa-chart-pie',
            'label' => $filiere->nom, // Code de la filière utilisé comme label
            'value' => $competences->count(),
        ];
    }

    return $stats;
}


}
