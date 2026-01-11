<?php

namespace Modules\PkgRealisationProjets\Services\Traits\RealisationProjet;

use Modules\PkgRealisationProjets\Models\RealisationProjet;

trait RealisationProjetCalculTrait
{
    /**
     * Met à jour les champs `progression_execution_cache` et `progression_validation_cache`
     * du RealisationProjet à partir des états des tâches associées.
     *
     * 🔁 Règle métier :
     * - `progression_execution_cache` : pourcentage de tâches arrivées à un état "finalisable"
     *   (actuellement : NOT_VALIDATED ou APPROVED).
     * - `progression_validation_cache` : pourcentage de tâches validées pédagogiquement (APPROVED uniquement).
     *
     * Les états sont calculés à partir des `workflowTache.code` liés aux `etatRealisationTache`
     * de chaque tâche du projet concerné.
     *
     * Si aucune tâche n’est associée au projet, les deux progressions sont mises à zéro.
     *
     * @param RealisationProjet $projet Le projet à analyser.
     * @return void
     */
    public function mettreAJourProgressionDepuisEtatDesTaches(RealisationProjet $projet): void
    {
        $realisationTaches = $projet->realisationTaches;

        if ($realisationTaches->isEmpty()) {
            $projet->update([
                'progression_execution_cache' => 0,
                'progression_validation_cache' => 0,
            ]);
            return;
        }

        $total = $realisationTaches->count();

        // États d'exécution (entre IN_PROGRESS et LIVE_CODING inclus)
        $executionCodes = ['NOT_VALIDATED', 'APPROVED'];

        // États de validation (approuvés uniquement)
        $validationCodes = ['APPROVED'];

        $executionCount = $realisationTaches->filter(function ($tache) use ($executionCodes) {
            return in_array(optional($tache->etatRealisationTache->workflowTache)->code, $executionCodes);
        })->count();

        $validationCount = $realisationTaches->filter(function ($tache) use ($validationCodes) {
            return in_array(optional($tache->etatRealisationTache->workflowTache)->code, $validationCodes);
        })->count();

        $projet->update([
            'progression_execution_cache' => round(($executionCount / $total) * 100, 2),
            'progression_validation_cache' => round(($validationCount / $total) * 100, 2),
        ]);
    }


    /**
     * Calcule et met à jour la note totale (`note_cache`) et le barème (`bareme_cache`)
     * du projet à partir des tâches notées uniquement.
     *
     * 🧠 Règles métier :
     * - note_cache : somme des `note` des tâches du projet.
     * - bareme_cache : somme des `bareme` uniquement pour les tâches qui ont une `note` non nulle.
     *
     * @param RealisationProjet $projet
     * @return void
     */
    public function calculerNoteEtBaremeDepuisTaches(RealisationProjet $projet): void
    {
        $realisationTaches = $projet->realisationTaches;

        if ($realisationTaches->isEmpty()) {
            $projet->update([
                'note_cache' => 0,
                'bareme_cache' => 0,
            ]);
            return;
        }

        $noteTotale = 0;
        $baremeTotal = 0;

        foreach ($realisationTaches as $tache) {
            if (!is_null($tache->note)) {
                $noteTotale += $tache->note;
                $baremeTotal += $tache->tache->note ?? 0;
            }
        }

        $projet->update([
            'note_cache' => round($noteTotale, 2),
            'bareme_cache' => round($baremeTotal, 2),
        ]);
    }
}
