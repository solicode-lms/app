<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;

/**
 * Trait dédié à la gestion des remédiations pédagogiques sur les tâches :
 * - Résolution ou activation dans la table `remediations`.
 * - Détection d'une remédiation antérieure lors d'une reprise de travail.
 */
trait RealisationTacheRemediationTrait
{
    /**
     * Synchronise l'état de remédiation dans la table `remediations` suite à la mise à jour d'une tâche.
     *
     * @param RealisationTache $realisationTache
     * @return void
     */
    protected function synchroniserRemediationApresUpdate(RealisationTache $realisationTache): void
    {
        if (!Schema::hasTable('remediations')) {
            return;
        }

        $nouvelEtat = EtatRealisationTache::with('workflowTache')->find($realisationTache->etat_realisation_tache_id);
        $wfCode = optional($nouvelEtat?->workflowTache)->code;

        if (in_array($wfCode, ['VALIDEE', 'APPROVED'])) {
            // Remédiation résolue
            DB::table('remediations')
                ->where('realisation_tache_id', $realisationTache->id)
                ->where('est_resolu', 0)
                ->update([
                    'est_resolu' => 1,
                    'updated_at' => now(),
                ]);
        } elseif ($wfCode === 'EN_REMEDIATION') {
            $existsRem = DB::table('remediations')
                ->where('realisation_tache_id', $realisationTache->id)
                ->exists();

            if (!$existsRem) {
                $formateurId = $realisationTache->realisationProjet?->affectationProjet?->projet?->formateur_id ?? 1;
                DB::table('remediations')->insert([
                    'realisation_tache_id' => $realisationTache->id,
                    'formateur_id' => $formateurId,
                    'type_remediation' => 'INDIVIDUELLE',
                    'diagnostic' => "Consolidation didactique requise",
                    'est_resolu' => 0,
                    'reference' => 'REM_' . $realisationTache->id . '_' . date('Ymd') . '_' . uniqid(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('remediations')
                    ->where('realisation_tache_id', $realisationTache->id)
                    ->where('est_resolu', 1)
                    ->update([
                        'est_resolu' => 0,
                        'updated_at' => now(),
                    ]);
            }
        }
    }

    /**
     * Vérifie si une tâche était en remédiation avant un épisode d'absence.
     *
     * @param RealisationTache $realisationTache
     * @return bool
     */
    public function verifierSiTacheEnRemediationAvantAbsence(RealisationTache $realisationTache): bool
    {
        $etaitEnRemediation = false;

        // 1. Vérification dans la table remediations
        if (Schema::hasTable('remediations')) {
            $etaitEnRemediation = DB::table('remediations')
                ->where('realisation_tache_id', $realisationTache->id)
                ->where('est_resolu', 0)
                ->exists();
        }

        // 2. Vérification de secours dans l'historique des changements d'état
        if (!$etaitEnRemediation) {
            $dernierChangement = HistoriqueRealisationTache::where('realisation_tache_id', $realisationTache->id)
                ->where('changement', 'like', '%État%')
                ->where('changement', 'not like', '%Absent%')
                ->latest('id')
                ->first();

            if ($dernierChangement) {
                $chg = mb_strtolower($dernierChangement->changement);
                $etaitEnRemediation = (str_contains($chg, 'remédiation') || str_contains($chg, 'remediation'));
            }
        }

        return $etaitEnRemediation;
    }
}
