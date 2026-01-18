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
 * 
 * Architecture modulaire via Traits :
 * @uses Traits\Projet\ProjetCrudTrait Gestion du cycle de vie CRUD et Hooks (beforeCreateRules, afterCreateRules).
 * @uses Traits\Projet\ProjetActionsTrait Actions métier spécifiques (import, export, génération de contenu).
 * @uses Traits\Projet\ProjetCalculTrait Calculs et enrichissement de données (statistiques, agrégations).
 * @uses Traits\Projet\ProjetRelationsTrait Gestion des relations complexes et synchronisations avec entités liées.
 * 
 * @see docs/1.scenarios/PkgCreationProjet/Projet/creation_projet_libre.scenario.mmd Scénario: Création Projet Libre
 */
class ProjetService extends BaseProjetService
{
    use ProjetCrudTrait,
        ProjetActionsTrait,
        ProjetCalculTrait,
        ProjetRelationsTrait;

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
     * Cette configuration définit l'ordre et les propriétés des tâches en fonction
     * des phases de projet définies en base de données.
     *
     * @param mixed $session La session de formation (pour les titres/descriptions dynamiques).
     * @return array
     */
    public static function getTasksConfig($session)
    {
        $tasksConfig = [];

        // Récupérer les phases d'évaluation nécessaires
        $phasesEval = \Modules\PkgCompetences\Models\PhaseEvaluation::pluck('id', 'code')->toArray();

        // Utilisation du modèle dans PkgCreationTache comme défini par l'utilisateur
        $phasesProjet = \Modules\PkgCreationTache\Models\PhaseProjet::orderBy('ordre')->get();

        foreach ($phasesProjet as $phase) {
            switch ($phase->reference) {
                case 'ANALYSE':
                    $tasksConfig[] = [
                        'titre' => 'Analyse',
                        'description' => 'Analyse du projet',
                        'phase_evaluation_id' => null,
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'APPRENTISSAGE':
                    $tasksConfig[] = [
                        'type' => 'Tutoriels',
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'PROTOTYPE':
                    $tasksConfig[] = [
                        'titre' => optional($session)->titre_prototype ? "Prototype : " . optional($session)->titre_prototype : 'Prototype',
                        'description' => trim((optional($session)->description_prototype ?? '') . "</br><b>Contraintes</b>" . (optional($session)->contraintes_prototype ?? '')),
                        'phase_evaluation_id' => null, // Pas d'évaluation sur le prototype statique seul
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'LIVE_CODING':
                    $tasksConfig[] = [
                        'titre' => 'Live Coding (Prototype)',
                        'description' => 'Validation des compétences via Live Coding sur le prototype.',
                        'phase_evaluation_id' => $phasesEval['N2'] ?? null, // C'est ici qu'on évaluation N2 (Adapter)
                        // Note calculée automatiquement dans TacheService
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'CONCEPTION':
                    $tasksConfig[] = [
                        'titre' => 'Conception',
                        'description' => 'Conception du projet',
                        'phase_evaluation_id' => null,
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'REALISATION':
                    $tasksConfig[] = [
                        'titre' => 'Réalisation',
                        'description' => trim((optional($session)->description_projet ?? '') . "</br><b>Contraintes</b>" . (optional($session)->contraintes_projet ?? '')),
                        'phase_evaluation_id' => $phasesEval['N3'] ?? null,
                        // Note calculée automatiquement dans TacheService
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'BESOINS':
                    // Tâche optionnelle ou future
                    break;

                case 'LIVRAISON':
                case 'PRESENTATION':
                case 'CLOTURE':
                    // Pas de tâches automatiques pour l'instant
                    break;
            }
        }

        return $tasksConfig;
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

    /**
     * Corrige et assigne les phases de projet aux tâches existantes qui n'en ont pas.
     * Utile pour la migration des anciens projets vers la nouvelle structure par phases.
     *
     * Règles d'assignation :
     * - Tâches N1 -> Phase APPRENTISSAGE
     * - Tâches N2 -> Phase PROTOTYPE
     * - Tâches N3 -> Phase REALISATION
     * - Titre 'Analyse' -> Phase ANALYSE
     * - Titre 'Conception' -> Phase CONCEPTION
     * - Titre 'Présentation' -> Phase PRESENTATION
     *
     * @param int $projetId L'identifiant du projet à corriger.
     * @return void
     */
    public function fixPhasesForExistingTasks($projetId)
    {
        // 1. Récupération des IDs des Phases Projet
        $phases = \Modules\PkgCreationTache\Models\PhaseProjet::all()->pluck('id', 'reference');

        // 2. Récupération des IDs des Phases Evaluation
        $phaseEvaluations = \Modules\PkgCompetences\Models\PhaseEvaluation::all()->pluck('id', 'code');

        // 3. Mise à jour par Niveau d'Evaluation (Prioritaire)
        if (isset($phaseEvaluations['N1']) && isset($phases['APPRENTISSAGE'])) {
            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->where('phase_evaluation_id', $phaseEvaluations['N1'])
                ->update(['phase_projet_id' => $phases['APPRENTISSAGE']]);
        }

        if (isset($phaseEvaluations['N2']) && isset($phases['PROTOTYPE'])) {
            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->where('phase_evaluation_id', $phaseEvaluations['N2'])
                ->update(['phase_projet_id' => $phases['PROTOTYPE']]);
        }

        if (isset($phaseEvaluations['N3']) && isset($phases['REALISATION'])) {
            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->where('phase_evaluation_id', $phaseEvaluations['N3'])
                ->update(['phase_projet_id' => $phases['REALISATION']]);
        }

        // 4. Mise à jour par Titre (pour les tâches sans phase d'éval ou spécifiques)
        // Note : On ne surcharge pas si déjà défini, ou on force selon la logique. Ici on cible celles sans phase ou mal définies.

        if (isset($phases['ANALYSE'])) {
            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->whereNull('phase_projet_id') // On cible celles qui restent
                ->where('titre', 'like', '%Analyse%')
                ->update(['phase_projet_id' => $phases['ANALYSE']]);
        }

        if (isset($phases['CONCEPTION'])) {
            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->whereNull('phase_projet_id')
                ->where('titre', 'like', '%Conception%')
                ->update(['phase_projet_id' => $phases['CONCEPTION']]);
        }

        if (isset($phases['PRESENTATION'])) {
            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->whereNull('phase_projet_id')
                ->where('titre', 'like', '%Présentation%')
                ->update(['phase_projet_id' => $phases['PRESENTATION']]);
        }

        // Optionnel : Cas par défaut pour tout ce qui reste -> BESOINS ou autre ?
    }
}
