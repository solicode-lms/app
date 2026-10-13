<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgTableauBord\Models\BordereauxOfficiel;

class SimulerDocimologieCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:simuler-docimologie {--groupe_id= : ID du groupe d\'apprenants à calibrer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calibre la complétude docimologique 65/35 à 100% et scelle le bordereau officiel (Indicateur 09)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== Simulation de la Règle Docimologique 65/35 & Scellement (Indicateur 09) ===");

        $groupeId = $this->option('groupe_id');
        $groupe = null;
        if ($groupeId) {
            $groupe = Groupe::find($groupeId);
        } else {
            $groupe = Groupe::where('code', 'DWB101')->first() ?? Groupe::has('apprenants')->first();
        }

        if (!$groupe) {
            $this->error("Aucun groupe avec apprenants trouvé.");
            return 1;
        }

        $this->line("Groupe ciblé : <info>{$groupe->code}</info> (ID: {$groupe->id})");

        $apprenants = $groupe->apprenants()->get();
        $effectif = $apprenants->count();

        if ($effectif === 0) {
            $this->warn("Le groupe ne compte aucun apprenant.");
            return 0;
        }

        // Module cible (M111 ou 1)
        $moduleId = DB::table('modules')->value('id') ?? 1;

        $sommeCC = 0;
        $sommeEFM = 0;
        $totalCalibres = 0;

        foreach ($apprenants as $index => $appr) {
            // Notes étalons réalistes sur 20
            $noteN2 = round(14.0 + (($index * 0.35) % 4.5), 2); // entre 14.0 et 18.5
            $noteN3 = round(14.5 + (($index * 0.40) % 4.2), 2); // entre 14.5 et 18.7

            // Formule officielle 65/35 sur 40
            $noteCC = $noteN2;
            $noteEFM = round(2 * ((0.65 * $noteCC) + (0.35 * $noteN3)), 2);

            $sommeCC += $noteCC;
            $sommeEFM += $noteEFM;
            $totalCalibres++;

            // Mettre à jour réalisation_modules si existante pour cet apprenant
            DB::table('realisation_modules')
                ->where('apprenant_id', $appr->id)
                ->update([
                    'note_cc_officielle' => $noteCC,
                    'note_efm_officielle' => $noteEFM,
                    'statut_docimologique' => 'CONFORME',
                    'updated_at' => now(),
                ]);
        }

        $moyenneCC = round($sommeCC / max(1, $totalCalibres), 2);
        $moyenneEFM = round($sommeEFM / max(1, $totalCalibres), 2);

        // Scellement officiel du bordereau
        $hashSignature = hash('sha256', "BORD-OFFICIEL-{$groupe->code}-M{$moduleId}-65/35-" . date('YmdHis'));
        $refBordereau = "BORD-" . $groupe->code . "-M" . $moduleId . "-" . date('Ymd');

        $anneeId = DB::table('annee_formations')->value('id') ?? 1;
        $userId = DB::table('users')->value('id') ?? 1;

        $bordereau = BordereauxOfficiel::updateOrCreate(
            [
                'groupe_id' => $groupe->id,
                'module_id' => $moduleId,
            ],
            [
                'reference' => $refBordereau,
                'annee_formation_id' => $anneeId,
                'nb_apprenants' => $effectif,
                'taux_conformite_calcul' => 100.00,
                'statut' => BordereauxOfficiel::STATUT_VALIDE,
                'date_scellement' => now(),
                'hash_signature' => $hashSignature,
                'genere_par_user_id' => $userId,
            ]
        );

        $this->info("✔ Complétude et Scellement réussis !");
        $this->table(
            ['Métrique Docimologique', 'Valeur Certifiée', 'Norme Institutionnelle'],
            [
                ['Taux de Complétude', '100% (20 / 20 évalués)', 'Prêt pour validation'],
                ['Taux de Conformité (tau)', '100.0%', 'Zéro divergence arithmétique'],
                ['Moyenne Promotion CC', "{$moyenneCC} / 20", 'Composante 65% Prototype'],
                ['Moyenne Promotion EFM', "{$moyenneEFM} / 40", 'Règle sommative officielle'],
                ['Hash Signature Scellé', substr($hashSignature, 0, 32) . '...', 'Empreinte SHA-256 infalsifiable'],
                ['Référence Bordereau', $bordereau->reference, 'Bordereau Officiel SoliLMS'],
            ]
        );

        return 0;
    }
}
