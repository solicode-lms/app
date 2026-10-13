<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgTableauBord\Models\LogActiviteFormateur;

class SimulerActiviteFormateursCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:simuler-activite-formateurs {--reset : Réinitialise les logs avant de simuler}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simule des logs de télémétrie pour les formateurs (Indicateur 04 — C_logistique)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== Simulation des Données de Charge Administrative (Indicateur 04 — C_logistique) ===");

        $semaine = now()->format('o-\WW');
        $today = now()->toDateString();

        if ($this->option('reset')) {
            $deleted = DB::table('log_activite_formateurs')->where('semaine_annee', $semaine)->delete();
            $this->warn("Suppression de $deleted logs existants pour la semaine $semaine.");
        }

        $formateurs = Formateur::all();
        if ($formateurs->isEmpty()) {
            $this->error("Aucun formateur trouvé en base de données.");
            return 1;
        }

        $this->info("Formateurs trouvés : " . $formateurs->count());

        $scenarios = [
            // Scénario 1 : Conforme maquette (1h45 = 50 min Kanban, 40 min FIFO, 15 min Bordereaux)
            [
                LogActiviteFormateur::TYPE_PILOTAGE_KANBAN => 3000,
                LogActiviteFormateur::TYPE_GESTION_FILE_FIFO => 2400,
                LogActiviteFormateur::TYPE_EXPORT_BORDEREAU => 900,
            ],
            // Scénario 2 : 2h10 (45 min Kanban, 55 min FIFO, 30 min Bordereaux)
            [
                LogActiviteFormateur::TYPE_PILOTAGE_KANBAN => 2700,
                LogActiviteFormateur::TYPE_GESTION_FILE_FIFO => 3300,
                LogActiviteFormateur::TYPE_EXPORT_BORDEREAU => 1800,
            ],
            // Scénario 3 : 2h45 (Charge notable) (1h15 Kanban, 1h00 FIFO, 30 min Bordereaux)
            [
                LogActiviteFormateur::TYPE_PILOTAGE_KANBAN => 4500,
                LogActiviteFormateur::TYPE_GESTION_FILE_FIFO => 3600,
                LogActiviteFormateur::TYPE_EXPORT_BORDEREAU => 1800,
            ],
            // Scénario 4 : 1h15 (Allégé) (35 min Kanban, 30 min FIFO, 10 min Bordereaux)
            [
                LogActiviteFormateur::TYPE_PILOTAGE_KANBAN => 2100,
                LogActiviteFormateur::TYPE_GESTION_FILE_FIFO => 1800,
                LogActiviteFormateur::TYPE_EXPORT_BORDEREAU => 600,
            ],
        ];

        $totalLogsInseres = 0;

        foreach ($formateurs as $index => $formateur) {
            $scenario = $scenarios[$index % count($scenarios)];

            foreach ($scenario as $type => $dureeSecondes) {
                $ref = 'LOG_SIM_' . $formateur->id . '_' . str_replace('-', '', $today) . '_' . $type;

                $existing = DB::table('log_activite_formateurs')
                    ->where('formateur_id', $formateur->id)
                    ->where('date_activite', $today)
                    ->where('type_activite', $type)
                    ->first();

                if ($existing) {
                    DB::table('log_activite_formateurs')
                        ->where('id', $existing->id)
                        ->update([
                            'duree_secondes' => $dureeSecondes,
                            'updated_at' => now(),
                        ]);
                } else {
                    DB::table('log_activite_formateurs')->insert([
                        'formateur_id' => $formateur->id,
                        'type_activite' => $type,
                        'duree_secondes' => $dureeSecondes,
                        'date_activite' => $today,
                        'semaine_annee' => $semaine,
                        'reference' => $ref,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $totalLogsInseres++;
                }
            }

            $nom = trim(($formateur->prenom ?? '') . ' ' . ($formateur->nom ?? ''));
            $totalHeures = round(array_sum($scenario) / 3600.0, 1);
            $this->line(" - Formateur [$nom] : $totalHeures h simulées pour la semaine $semaine.");
        }

        $this->info("Simulation terminée avec succès !");
        return 0;
    }
}
