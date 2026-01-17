<?php

namespace Modules\PkgCreationTache\Services\Traits\Tache;

use Modules\PkgCreationTache\Models\PhaseProjet;
use Modules\PkgCreationTache\Models\Tache;

/**
 * Trait TacheActionsTrait
 * 
 * Actions métier spécifiques et helpers de génération.
 */
trait TacheActionsTrait
{
    /**
     * Crée les tâches (Tutoriels) associées aux chapitres d'une UA.
     */
    public function createTasksFromUa($projetId, $ua)
    {
        if (is_numeric($ua)) {
            $ua = \Modules\PkgCompetences\Models\UniteApprentissage::with('chapitres')->find($ua);
        }

        if (!$ua || $ua->chapitres->isEmpty())
            return;

        $phaseN1Id = \Modules\PkgCompetences\Models\PhaseEvaluation::where('code', 'N1')->value('id');
        $phaseApprentissage = PhaseProjet::where('reference', 'APPRENTISSAGE')->first();
        $phaseProjetId = $phaseApprentissage ? $phaseApprentissage->id : null;

        $ordre = 1;
        $maxOrdrePhase = 0;

        if ($phaseProjetId) {
            $maxOrdrePhase = Tache::where('projet_id', $projetId)
                ->where('phase_projet_id', $phaseProjetId)->max('ordre');
        }

        if ($maxOrdrePhase) {
            $ordre = $maxOrdrePhase + 1;
        } else {
            $maxOrdrePrecedent = 0;
            if ($phaseApprentissage) {
                $previousPhaseIds = PhaseProjet::where('ordre', '<', $phaseApprentissage->ordre)->pluck('id');
                if ($previousPhaseIds->isNotEmpty()) {
                    $maxOrdrePrecedent = Tache::where('projet_id', $projetId)
                        ->whereIn('phase_projet_id', $previousPhaseIds)->max('ordre');
                }
            }
            $ordre = $maxOrdrePrecedent ? $maxOrdrePrecedent + 1 : 1;
        }

        foreach ($ua->chapitres as $chapitre) {
            $exists = Tache::where('projet_id', $projetId)
                ->where('titre', 'Tutoriel : ' . $chapitre->nom)->exists();

            if (!$exists) {
                $this->create([
                    'projet_id' => $projetId,
                    'titre' => 'Tutoriel : ' . $chapitre->nom,
                    'description' => "Tutoriel lié au chapitre : " . $chapitre->nom,
                    'phase_evaluation_id' => $phaseN1Id,
                    'priorite' => $ordre,
                    'ordre' => $ordre,
                    'chapitre_id' => $chapitre->id,
                    'phase_projet_id' => $phaseProjetId
                ]);
                $ordre++;
            }
        }
    }
}
