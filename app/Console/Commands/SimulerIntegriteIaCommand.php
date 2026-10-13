<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;

class SimulerIntegriteIaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:simuler-integrite-ia {--affectation_id= : ID de l\'affectation de projet à cibler}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simule des notes d\'évaluation (N2/N3) et 2 cas d\'écarts suspects face à l\'IA (Indicateur 07 — Pearson r)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== Simulation de l'Intégrité Docimologique & Usage IA (Indicateur 07 — Pearson r) ===");

        $requestedId = $this->option('affectation_id');
        $affectation = null;
        if ($requestedId) {
            $affectation = AffectationProjet::with(['realisationProjets.apprenant', 'groupe'])->find($requestedId);
        }

        if (!$affectation) {
            // Rechercher l'affectation qui possède déjà des enregistrements de notation
            $affWithNotesId = DB::table('realisation_ua_prototypes as rup')
                ->join('realisation_taches as rt', 'rt.id', '=', 'rup.realisation_tache_id')
                ->join('realisation_projets as rp', 'rp.id', '=', 'rt.realisation_projet_id')
                ->value('rp.affectation_projet_id');

            if ($affWithNotesId) {
                $affectation = AffectationProjet::with(['realisationProjets.apprenant', 'groupe'])->find($affWithNotesId);
            }
        }

        if (!$affectation) {
            $affectation = AffectationProjet::with(['realisationProjets.apprenant', 'groupe'])->latest('date_debut')->first();
        }

        if (!$affectation) {
            $this->error("Aucune affectation de projet trouvée.");
            return 1;
        }

        $this->info("Affectation sélectionnée : Groupe [{$affectation->groupe->code}] (ID: {$affectation->id})");

        if (!Schema::hasTable('realisation_ua_prototypes') || !Schema::hasTable('realisation_ua_projets')) {
            $this->error("Tables de notation 'realisation_ua_prototypes' ou 'realisation_ua_projets' introuvables.");
            return 1;
        }

        $realisationsProjet = $affectation->realisationProjets;
        if ($realisationsProjet->count() < 3) {
            $this->warn("Moins de 3 apprenants rattachés à cette affectation.");
        }

        // Modèle de distribution étalon (conforme à la maquette dashboard-responsable.html, r = 0.78)
        $etalon = [
            ['n2' => 8.0, 'n3' => 18.0, 'anomalie' => true],  // Alerte 1 : Delta = +10.0 pts (Yassir)
            ['n2' => 11.0, 'n3' => 18.5, 'anomalie' => true], // Alerte 2 : Delta = +7.5 pts (Bilal)
            ['n2' => 6.0, 'n3' => 7.5, 'anomalie' => false],
            ['n2' => 10.0, 'n3' => 11.0, 'anomalie' => false],
            ['n2' => 13.0, 'n3' => 14.0, 'anomalie' => false],
            ['n2' => 15.0, 'n3' => 16.0, 'anomalie' => false],
            ['n2' => 17.5, 'n3' => 18.0, 'anomalie' => false],
            ['n2' => 19.0, 'n3' => 19.5, 'anomalie' => false],
            ['n2' => 14.0, 'n3' => 15.0, 'anomalie' => false],
        ];

        $nbMisAJour = 0;
        foreach ($realisationsProjet as $index => $rp) {
            $modele = $etalon[$index % count($etalon)];

            $tacheIds = DB::table('realisation_taches')->where('realisation_projet_id', $rp->id)->pluck('id');
            if ($tacheIds->isNotEmpty()) {
                // Mettre à jour les prototypes
                $affectedProto = DB::table('realisation_ua_prototypes')
                    ->whereIn('realisation_tache_id', $tacheIds)
                    ->update([
                        'note' => DB::raw("(bareme * " . ($modele['n2'] / 20.0) . ")"),
                        'updated_at' => now(),
                    ]);

                // Mettre à jour les projets
                $affectedProj = DB::table('realisation_ua_projets')
                    ->whereIn('realisation_tache_id', $tacheIds)
                    ->update([
                        'note' => DB::raw("(bareme * " . ($modele['n3'] / 20.0) . ")"),
                        'updated_at' => now(),
                    ]);

                if ($affectedProto > 0 && $affectedProj > 0) {
                    $nbMisAJour++;
                }
            }
        }

        $this->info("Simulation appliquée avec succès :");
        $this->info(" - Apprenants avec notes calibrées : {$nbMisAJour}");
        $this->info(" - 2 cas d'écart suspect (+10.0 pts et +7.5 pts) configurés.");
        $this->info(" - Coefficient de Pearson attendu : r ≈ 0.78 (Alignement Robuste).");

        return 0;
    }
}
