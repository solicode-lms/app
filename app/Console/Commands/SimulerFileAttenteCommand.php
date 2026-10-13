<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;

class SimulerFileAttenteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:simuler-file-attente {--reset : Réinitialise les états de file avant de simuler}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simule des apprenants en file d\'attente Live Coding (Indicateur 05 — T_file)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== Simulation de la File d'Attente FIFO (Indicateur 05 — T_file) ===");

        $affectation = AffectationProjet::with(['realisationProjets.realisationTaches', 'groupe'])->latest('date_debut')->first();
        if (!$affectation) {
            $this->error("Aucune affectation de projet trouvée.");
            return 1;
        }

        $this->info("Affectation sélectionnée : Groupe [{$affectation->groupe->code}]");

        // Recherche des états correspondants
        $etatReady = EtatRealisationTache::whereHas('workflowTache', fn($q) => $q->where('code', 'READY_FOR_LIVE_CODING'))->first();
        $etatInLive = EtatRealisationTache::whereHas('workflowTache', fn($q) => $q->where('code', 'IN_LIVE_CODING'))->first();

        if (!$etatReady || !$etatInLive) {
            $this->error("États de workflow READY_FOR_LIVE_CODING ou IN_LIVE_CODING introuvables.");
            return 1;
        }

        $realisationsProjet = $affectation->realisationProjets;
        if ($realisationsProjet->count() < 2) {
            $this->warn("Moins de 2 apprenants rattachés à cette affectation.");
        }

        $hasCacheCols = Schema::hasColumn('realisation_taches', 'cache_date_entree_file');

        $scenarios = [
            // Apprenant 1 : En Live Coding Actif (démarré après 10 min d'attente)
            ['etat_id' => $etatInLive->id, 'attente_minutes' => 10, 'in_live' => true],
            // Apprenant 2 : En attente depuis 28 min (Suivant)
            ['etat_id' => $etatReady->id, 'attente_minutes' => 28, 'in_live' => false],
            // Apprenant 3 : En attente depuis 18 min
            ['etat_id' => $etatReady->id, 'attente_minutes' => 18, 'in_live' => false],
            // Apprenant 4 : En attente depuis 8 min
            ['etat_id' => $etatReady->id, 'attente_minutes' => 8, 'in_live' => false],
        ];

        $tachesModifiees = 0;

        foreach ($realisationsProjet as $index => $rp) {
            if (!isset($scenarios[$index])) {
                break;
            }

            $scenario = $scenarios[$index];
            $tache = $rp->realisationTaches->first();
            if (!$tache) {
                continue;
            }

            $dateEntree = now()->subMinutes($scenario['attente_minutes']);
            $updates = [
                'etat_realisation_tache_id' => $scenario['etat_id'],
                'updated_at' => now(),
            ];

            if ($hasCacheCols) {
                $updates['cache_date_entree_file'] = $dateEntree;
                if ($scenario['in_live']) {
                    $updates['cache_date_debut_live_coding'] = now()->subMinutes(5);
                    $updates['cache_delai_attente_minutes'] = $scenario['attente_minutes'];
                } else {
                    $updates['cache_date_debut_live_coding'] = null;
                    $updates['cache_delai_attente_minutes'] = null;
                }
            }

            DB::table('realisation_taches')->where('id', $tache->id)->update($updates);
            $tachesModifiees++;

            $nom = trim(($rp->apprenant->prenom ?? '') . ' ' . ($rp->apprenant->nom ?? ''));
            $statutTexte = $scenario['in_live'] ? "En Live Coding Actif" : "En file (attente {$scenario['attente_minutes']} min)";
            $this->line(" - Apprenant [$nom] : $statutTexte");
        }

        $this->info("Simulation terminée : $tachesModifiees tâches mises à jour dans la file d'attente.");
        return 0;
    }
}
