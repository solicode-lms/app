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
    protected $signature = 'dashboard:simuler-couverture-pedagogique {--groupe_id= : ID du groupe d\'apprenants à analyser} {--calibrate : Calibre des données de démonstration (~85% et 2 impasses)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyse et audit de la couverture pédagogique nationale (ICP) et des impasses didactiques (Indicateur 08)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== Analyse de la Couverture Pédagogique Nationale (Indicateur 08 — ICP) ===");

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

        // Mode Calibrage Démonstration uniquement si demandé explicitement
        if ($this->option('calibrate')) {
            $this->warn("Option --calibrate détectée : Calibration des données de démonstration en cours...");

            $etatDoneId = DB::table('etat_realisation_micro_competences')->where('code', 'DONE')->value('id') ?? 5;
            $etatEnCoursId = DB::table('etat_realisation_micro_competences')->where('code', 'IN_PROGRESS')->value('id') 
                ?? DB::table('etat_realisation_micro_competences')->where('id', '!=', $etatDoneId)->value('id') 
                ?? 1;

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
            $impasseIds = array_slice($existingMcIds, 0, 2);

            $totalValides = 0;
            $totalEntrees = 0;

            foreach ($existingMcIds as $mcId) {
                $estImpasse = in_array($mcId, $impasseIds);
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

            $this->info("✔ Données calibrées avec succès !");
        }

        // Mode Audit Standard sur Données Réelles via la couche Service
        $tbService = app(\Modules\PkgTableauBord\Services\TableauBordService::class);
        $detail = $tbService->getIndicateurCouvertureDetail(['selected_groupe_id' => $groupe->id]);

        $kpis = $detail['kpis'];
        $this->table(
            ['Métrique ICP', 'Valeur Réelle', 'Diagnostic Docimologique'],
            [
                ['ICP Global de la cohorte', $kpis['taux_icp_formate'], $kpis['statut']],
                ['Seuil réglementaire national', "≥ {$kpis['seuil_cible']}%", ($kpis['ecart_cible'] >= 0 ? "+{$kpis['ecart_cible']} pts" : "{$kpis['ecart_cible']} pts")],
                ['Effectif cohorte', "{$kpis['effectif']} apprenants", "Total prescriptions : {$kpis['total_prescrites']} MC"],
                ['Total validations effectives', "{$kpis['total_validations']} / {$kpis['total_requis']}", "Validations réalisées"],
                ['Taux d\'homogénéité (≥ 75%)', "{$kpis['taux_homogeneite']}%", "{$kpis['nb_homogenes']} / {$kpis['effectif']} stagiaires consolidés"],
                ['Impasses didactiques (< 50%)', "{$kpis['nb_impasses']} compétence(s)", $kpis['nb_impasses'] > 0 ? 'Ateliers de consolidation requis' : 'Excellence homogène'],
            ]
        );

        if (!empty($detail['impasses'])) {
            $this->warn("\n--- Détail des Impasses Didactiques Détectées (< 50%) ---");
            $rowsImpasses = [];
            foreach ($detail['impasses'] as $imp) {
                $rowsImpasses[] = [
                    $imp['code'],
                    $imp['titre'],
                    "{$imp['nb_valides']} / {$imp['effectif']}",
                    $imp['taux_formate'],
                    $imp['statut_label']
                ];
            }
            $this->table(['Code MC', 'Intitulé', 'Validations', 'Taux', 'Sévérité'], $rowsImpasses);
        }

        return 0;
    }
}
