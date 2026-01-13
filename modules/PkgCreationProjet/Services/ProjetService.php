<?php

namespace Modules\PkgCreationProjet\Services;

use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Services\Base\BaseProjetService;
use Modules\PkgSessions\Models\SessionFormation;
use Modules\Core\App\Exceptions\BlException;
use Modules\PkgCreationProjet\Services\Traits\Projet\ProjetActionsTrait;
use Modules\PkgCreationProjet\Services\Traits\Projet\ProjetCalculTrait;
use Modules\PkgCreationProjet\Services\Traits\Projet\ProjetRelationsTrait;
use Modules\PkgCreationProjet\Services\Traits\Projet\ProjetCrudTrait;

/**
 * Classe ProjetService pour gérer la persistance de l'entité Projet.
 */
class ProjetService extends BaseProjetService
{
    use ProjetActionsTrait, ProjetCalculTrait, ProjetRelationsTrait, ProjetCrudTrait;

    protected array $index_with_relations = [
        'filiere',
        'formateur',
        'livrables',
        'resources',
        'taches',
        'affectationProjets',
        'affectationProjets.groupe'
    ];



    /**
     * Retourne la configuration des tâches à générer pour un projet donné.
     * Cette configuration définit l'ordre et les propriétés des tâches (Analyse, Prototype, etc.).
     *
     * @param mixed $session La session de formation (pour les titres/descriptions dynamiques).
     * @param array $phases Les IDs des phases d'évaluation ['N1' => id, 'N2' => id, 'N3' => id].
     * @param array $notes Les notes calculées ['prototype' => float, 'realisation' => float].
     * @return array
     */
    public static function getTasksConfig($session, $phases, $notes)
    {
        return [
            [
                'nature' => 'Analyse',
                'titre' => 'Analyse',
                'description' => 'Analyse du projet',
                'phase_evaluation_id' => null,
                'note' => null,
            ],
            'MOBILISATIONS', // Marqueur pour insertion dynamique des tutoriels
            [
                'nature' => 'Réalisation', // Prototype est une phase de réalisation technique
                'titre' => optional($session)->titre_prototype ? "Prototype : " . optional($session)->titre_prototype : 'Prototype',
                'description' => trim((optional($session)->description_prototype ?? '') . "</br><b>Contraintes</b>" . (optional($session)->contraintes_prototype ?? '')),
                'phase_evaluation_id' => $phases['N2'] ?? null,
                'note' => $notes['prototype'] ?? 0,
            ],
            [
                'nature' => 'Conception',
                'titre' => 'Conception',
                'description' => 'Conception du projet',
                'phase_evaluation_id' => null,
                'note' => null,
            ],
            [
                'nature' => 'Réalisation',
                'titre' => 'Réalisation',
                'description' => trim((optional($session)->description_projet ?? '') . "</br><b>Contraintes</b>" . (optional($session)->contraintes_projet ?? '')),
                'phase_evaluation_id' => $phases['N3'] ?? null,
                'note' => $notes['realisation'] ?? 0,
            ]
        ];
    }

    /**
     * Définit l'ordre de tri par défaut pour les requêtes de projets.
     *
     * Trie les projets par la date de fin la plus récente de leurs affectations,
     * mettant en avant les projets actifs ou récemment terminés.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query La requête Eloquent.
     * @return \Illuminate\Database\Eloquent\Builder La requête triée.
     */
    // public function defaultSort($query)
    // {
    //     return $query
    //         ->withMax('affectationProjets', 'date_fin') // 🔥 Important
    //         ->orderBy('affectation_projets_max_date_fin', 'asc');
    // }
}
