<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;

class RecalculerRevisionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:recalculer-revisions {--force : Forcer le recalcul même si les données existent déjà}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Peuple et met à jour les données de cache pour l\'indicateur des boucles de révision (F_révision)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("=== Démarrage du rattrapage des données de Révision (Indicateur 03 — F_révision) ===");

        if (!Schema::hasColumn('realisation_taches', 'cache_nombre_revisions')) {
            $this->warn("⚠️ La colonne 'cache_nombre_revisions' n'existe pas encore en base de données. Veuillez exécuter 'php artisan migrate'.");
            return 1;
        }

        $isForce = (bool) $this->option('force');

        // Récupérer les IDs d'états correspondant au workflow REVISION_NECESSAIRE
        $etatRevisionIds = EtatRealisationTache::whereHas('workflowTache', function ($q) {
            $q->where('code', 'REVISION_NECESSAIRE');
        })->pluck('id')->toArray();

        $realisations = RealisationTache::with(['realisationProjet.affectationProjet.projet'])
            ->whereHas('realisationProjet.affectationProjet.projet', function ($q) {
                $q->sessionsFormation();
            })
            ->get();

        $this->info("Traitement de {$realisations->count()} réalisations de tâches...");

        $nbMisesAJour = 0;
        $totalRejets = 0;

        foreach ($realisations as $rt) {
            if (!$isForce && $rt->cache_nombre_revisions > 0) {
                $totalRejets += $rt->cache_nombre_revisions;
                continue;
            }

            // Compter les passages par REVISION_NECESSAIRE dans l'historique
            $nbRevisionsHistorique = HistoriqueRealisationTache::where('realisation_tache_id', $rt->id)
                ->where(function ($q) use ($etatRevisionIds) {
                    $q->where('changement', 'like', '%Révision nécessaire%')
                      ->orWhere('changement', 'like', '%REVISION_NECESSAIRE%');
                })
                ->count();

            // Si actuellement en révision nécessaire mais non tracé dans l'historique
            if ($nbRevisionsHistorique === 0 && in_array($rt->etat_realisation_tache_id, $etatRevisionIds)) {
                $nbRevisionsHistorique = 1;
            }

            if ($rt->cache_nombre_revisions !== $nbRevisionsHistorique) {
                \Illuminate\Support\Facades\DB::table('realisation_taches')
                    ->where('id', $rt->id)
                    ->update([
                        'cache_nombre_revisions' => $nbRevisionsHistorique
                    ]);
                $nbMisesAJour++;
            }

            $totalRejets += $nbRevisionsHistorique;
        }

        $this->info("=== Fin du traitement ===");
        $this->info("Réalisations mises à jour : {$nbMisesAJour}");
        $this->info("Total rejets comptabilisés : {$totalRejets}");

        return 0;
    }
}
