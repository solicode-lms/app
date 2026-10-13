<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\PkgApprenants\Models\Groupe;

class SimulerCouverturePedagogiqueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:simuler-couverture-pedagogique {--groupe_id= : ID du groupe d\'apprenants à calibrer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calibre la couverture pédagogique nationale (~85%) et 2 impasses didactiques (Indicateur 08 — ICP)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== Simulation de la Couverture Pédagogique Nationale (Indicateur 08 — ICP) ===");

        $groupeId = $this->option('groupe_id');
        $groupe = null;
        if ($groupeId) {
            $groupe = Groupe::find($groupeId);
        } else {
            // Prioriser DWB101 ou le premier groupe avec des RMC existantes
            $groupe = Groupe::where('code', 'DWB101')->first() ?? Groupe::has('apprenants')->first();
        }

        if (!$groupe) {
            $this->error("Aucun groupe avec apprenants trouvé.");
            return 1;
        }

        $this->line("Groupe ciblé : <info>{$groupe->code}</info> (ID: {$groupe->id})");

        $apprenantsIds = $groupe->apprenants()->pluck('apprenants.id');
        $effectif = $apprenantsIds->count();

        if ($effectif === 0) {
            $this->warn("Le groupe ne compte aucun apprenant.");
            return 0;
        }

        $etatDoneId = DB::table('etat_realisation_micro_competences')->where('code', 'DONE')->value('id') ?? 5;
        $etatEnCoursId = DB::table('etat_realisation_micro_competences')->where('code', 'IN_PROGRESS')->value('id') 
            ?? DB::table('etat_realisation_micro_competences')->where('id', '!=', $etatDoneId)->value('id') 
            ?? 1;

        // Récupérer les micro-compétences associées aux apprenants de ce groupe
        $existingMcIds = DB::table('realisation_micro_competences')
            ->whereIn('apprenant_id', $apprenantsIds)
            ->distinct('micro_competence_id')
            ->pluck('micro_competence_id')
            ->toArray();

        if (empty($existingMcIds)) {
            $this->warn("Aucune micro-compétence trouvée pour ce groupe.");
            return 0;
        }

        $totalMc = count($existingMcIds);
        $this->line("Micro-compétences détectées : <comment>{$totalMc}</comment> sur {$effectif} apprenants.");

        // Choisir 2 micro-compétences à calibrer en impasses didactiques (< 50%)
        $impasseIds = array_slice($existingMcIds, 0, 2);

        $totalValides = 0;
        $totalEntrees = 0;

        foreach ($existingMcIds as $mcId) {
            $estImpasse = in_array($mcId, $impasseIds);
            // Si impasse : seulement 30% des apprenants valident
            // Si standard : 90% des apprenants valident
            $seuilValidation = $estImpasse ? 0.30 : 0.90;

            $records = DB::table('realisation_micro_competences')
                ->whereIn('apprenant_id', $apprenantsIds)
                ->where('micro_competence_id', $mcId)
                ->orderBy('apprenant_id')
                ->get();

            $countForMc = $records->count();
            foreach ($records as $index => $rec) {
                $doitEtreValide = (($index / max(1, $countForMc)) < $seuilValidation);
                $etatId = $doitEtreValide ? $etatDoneId : $etatEnCoursId;

                DB::table('realisation_micro_competences')
                    ->where('id', $rec->id)
                    ->update([
                        'etat_realisation_micro_competence_id' => $etatId,
                        'progression_cache' => $doitEtreValide ? 100 : 40,
                        'note_cache' => $doitEtreValide ? 16 : 8,
                        'bareme_cache' => 20,
                        'updated_at' => now(),
                    ]);

                $totalEntrees++;
                if ($doitEtreValide) {
                    $totalValides++;
                }
            }
        }

        $icpFinal = round(($totalValides / max(1, $totalEntrees)) * 100, 1);

        $this->info("✔ Données synchronisées avec succès !");
        $this->table(
            ['Métrique ICP', 'Valeur simulée', 'Statut Didactique'],
            [
                ['ICP Global de la cohorte', "{$icpFinal}%", 'Optimal (~85% cible)'],
                ['Total validations', "{$totalValides} / {$totalEntrees}", "Sur {$totalMc} micro-compétences"],
                ['Micro-compétences en impasse (<50%)', count($impasseIds) . ' compétences', 'MC #' . implode(', #', $impasseIds) . ' en alerte'],
            ]
        );

        return 0;
    }
}
