<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgTableauBord\Models\Remediation;

class SimulerRemediationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:simuler-remediations {--reset : Réinitialise les remédiations et dates de simulation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simule des blocages post-Live Coding (+10 min) et des remédiations (Indicateur 06 — T_intervention)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== Simulation des Remédiations & Blocages (Indicateur 06 — T_intervention) ===");

        $affectation = AffectationProjet::with(['realisationProjets.realisationTaches.tache', 'groupe'])->latest('date_debut')->first();
        if (!$affectation) {
            $this->error("Aucune affectation de projet trouvée.");
            return 1;
        }

        $this->info("Affectation sélectionnée : Groupe [{$affectation->groupe->code}]");

        if ($this->option('reset')) {
            if (Schema::hasTable('remediations')) {
                $deleted = DB::table('remediations')->where('reference', 'LIKE', 'SIM_%')->delete();
                $this->info("Remédiations de simulation supprimées ({$deleted} lignes).");
            }
            if (Schema::hasColumn('affectation_projets', 'date_fin_live_coding_collectif')) {
                DB::table('affectation_projets')->where('id', $affectation->id)->update([
                    'date_fin_live_coding_collectif' => null,
                ]);
                $this->info("Date de fin de Live Coding collectif réinitialisée.");
            }
            return 0;
        }

        // 1. Mise à jour de la date de fin Live Coding collectif (24 minutes dans le passé)
        // Avec seuil = 10 min, le délai critique a expiré il y a 14 minutes (+14 min)
        if (Schema::hasColumn('affectation_projets', 'date_fin_live_coding_collectif')) {
            $seuil = $affectation->seuil_blocage_minutes ?? 10;
            DB::table('affectation_projets')->where('id', $affectation->id)->update([
                'date_fin_live_coding_collectif' => Carbon::now()->subMinutes(14 + (int) $seuil),
                'seuil_blocage_minutes' => $seuil,
            ]);
            $this->info("Date de fin Live Coding enregistrée (-" . (14 + (int) $seuil) . " min) avec seuil critique de {$seuil} min.");
        } else {
            $this->warn("Colonne date_fin_live_coding_collectif absente (migration requise).");
        }

        // 2. Création de remédiations simulées pour obtenir le ratio cible de 88%
        if (!Schema::hasTable('remediations')) {
            $this->warn("Table 'remediations' non présente. Veuillez exécuter 'php artisan migrate' pour stocker les remédiations.");
            return 0;
        }

        $formateur = Formateur::first();
        $formateurId = $formateur ? $formateur->id : 1;

        // Récupérer les tâches des apprenants de l'affectation
        $taches = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectation->id))
            ->take(8)
            ->get();

        if ($taches->isEmpty()) {
            $this->warn("Aucune réalisation de tâche trouvée pour cette affectation.");
            return 0;
        }

        // Remédier 7 tâches sur 8 (ou N-1 sur N) pour simuler ~88% de taux de prise en charge
        $totalCibles = min(8, $taches->count());
        $aRemedier = max(1, $totalCibles - 1);

        $nbInserts = 0;
        for ($i = 0; $i < $aRemedier; $i++) {
            $tache = $taches[$i];
            $exists = DB::table('remediations')->where('realisation_tache_id', $tache->id)->exists();
            if (!$exists) {
                DB::table('remediations')->insert([
                    'realisation_tache_id' => $tache->id,
                    'formateur_id' => $formateurId,
                    'type_remediation' => $i % 2 === 0 ? Remediation::TYPE_TRINOME : Remediation::TYPE_INDIVIDUELLE,
                    'diagnostic' => "Consolidation syntaxique et accompagnement post-Live Coding.",
                    'est_resolu' => true,
                    'reference' => 'SIM_REM_' . $tache->id . '_' . uniqid(),
                    'created_at' => Carbon::now()->subMinutes(5),
                    'updated_at' => Carbon::now(),
                ]);
                $nbInserts++;
            }
        }

        $this->info("Simulation terminée :");
        $this->info(" - Remédiations injectées : {$nbInserts}");
        $this->info(" - 1 apprenant laissé en attente de soutien (+14 min) pour alimenter la Flashbox Alerte Rouge.");
        $this->info(" - Taux de remédiation attendu : ~88% (Excellence).");

        return 0;
    }
}
