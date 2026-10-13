<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;

class RecalculerAmorcageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:recalculer-amorcage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Peuple et met à jour les données de cache pour l\'indicateur d\'amorçage (T_amorçage)';

    /**
     * Execute the console command.
     */
    public function handle(AffectationProjetService $affectationService): int
    {
        $this->info("=== Démarrage du rattrapage des données d'amorçage ===");

        // 1. Mise à jour de cache_date_lancement sur affectation_projets
        $affectations = AffectationProjet::all();
        $this->info("Traitement de {$affectations->count()} affectations...");

        foreach ($affectations as $aff) {
            $dateLancement = $affectationService->determinerDateLancement($aff);
            $this->line("Affectation #{$aff->id} ({$aff->reference}) -> Date lancement : {$dateLancement}");
        }

        // 2. Mise à jour de cache_date_premiere_soumission et cache_date_lancement sur realisation_projets
        $projets = RealisationProjet::with('affectationProjet')->get();
        $this->info("Traitement de {$projets->count()} réalisations de projets...");

        $nbMisesAJour = 0;
        foreach ($projets as $projet) {
            $updateData = [];

            // Si cache_date_lancement est vide, on prend celui de l'affectation
            if (is_null($projet->cache_date_lancement) && $projet->affectationProjet) {
                $updateData['cache_date_lancement'] = $projet->affectationProjet->cache_date_lancement 
                    ?? $affectationService->determinerDateLancement($projet->affectationProjet);
            }

            // Si cache_date_premiere_soumission est vide, on cherche dans HistoriqueRealisationTache ou les tâches soumises
            if (is_null($projet->cache_date_premiere_soumission)) {
                $premiereSoumission = HistoriqueRealisationTache::whereHas('realisationTache', function ($q) use ($projet) {
                        $q->where('realisation_projet_id', $projet->id);
                    })
                    ->where(function ($q) {
                        $q->where('changement', 'like', '%valider%')
                          ->orWhere('changement', 'like', '%TO_APPROVE%')
                          ->orWhere('changement', 'like', '%LIVE_CODING%')
                          ->orWhere('changement', 'like', '%APPROVED%')
                          ->orWhere('changement', 'like', '%Validé%');
                    })
                    ->min('created_at');

                // Si aucune trace dans l'historique mais que des tâches sont soumises / validées
                if (!$premiereSoumission) {
                    $hasSubmittedTasks = $projet->realisationTaches()
                        ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                            $q->whereIn('code', ['TO_APPROVE', 'READY_FOR_LIVE_CODING', 'IN_LIVE_CODING', 'APPROVED', 'NOT_VALIDATED']);
                        })
                        ->exists();

                    if ($hasSubmittedTasks && !empty($updateData['cache_date_lancement'])) {
                        $premiereSoumission = Carbon::parse($updateData['cache_date_lancement'])->addMinutes(75);
                    }
                }

                if ($premiereSoumission) {
                    $updateData['cache_date_premiere_soumission'] = Carbon::parse($premiereSoumission);
                }
            }

            if (!empty($updateData)) {
                $projet->update($updateData);
                $nbMisesAJour++;
            }
        }

        $this->info("✅ Rattrapage terminé avec succès ! {$nbMisesAJour} projets mis à jour.");
        return Command::SUCCESS;
    }
}
