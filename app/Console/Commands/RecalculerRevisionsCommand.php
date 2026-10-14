<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Models\RevisionTache;
use Modules\PkgRealisationTache\Services\RevisionTacheService;

class RecalculerRevisionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:recalculer-revisions {--force : Forcer le recalcul et la régénération des révisions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Peuple la table revision_taches depuis l\'historique et met à jour le cache (Indicateur 03 — F_révision)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("=== Démarrage du rattrapage des données de Révision (Indicateur 03 — F_révision) ===");

        $isForce = (bool) $this->option('force');
        $service = app(RevisionTacheService::class);

        if ($isForce) {
            $this->warn("Option --force détectée : purge des révisions existantes...");
            RevisionTache::query()->delete();
        }

        // Trouver toutes les tâches concernées par une révision ou une remarque formateur
        $allRtIds = HistoriqueRealisationTache::where(function ($q) {
            $q->where('changement', 'like', '%REVISION_NECESSAIRE%')
              ->orWhere('changement', 'like', '%Remarques - formateur%');
        })
        ->orWhereIn('realisation_tache_id', RealisationTache::where('cache_nombre_revisions', '>', 0)->pluck('id'))
        ->pluck('realisation_tache_id')
        ->unique()
        ->values();

        $this->info("Traitement de " . count($allRtIds) . " réalisations de tâches...");

        $totalCrees = 0;
        $totalTachesMisesAJour = 0;

        foreach ($allRtIds as $rtId) {
            $realisationTache = RealisationTache::find($rtId);
            if (!$realisationTache) {
                continue;
            }

            // Si des révisions existent déjà et que --force n'est pas activé, resynchroniser et passer
            $existants = RevisionTache::where('realisation_tache_id', $rtId)->count();
            if ($existants > 0 && !$isForce) {
                $service->synchroniserCacheRevisionDepuisRevisionTaches($rtId);
                continue;
            }

            $historiques = HistoriqueRealisationTache::where('realisation_tache_id', $rtId)
                ->where(function ($q) {
                    $q->where('changement', 'like', '%REVISION_NECESSAIRE%')
                      ->orWhere('changement', 'like', '%Remarques - formateur%');
                })
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            // Regroupement par session de révision (fenêtre de 15 minutes)
            $groupes = [];
            $groupeCourant = [];

            foreach ($historiques as $h) {
                if (empty($groupeCourant)) {
                    $groupeCourant[] = $h;
                } else {
                    $dernier = end($groupeCourant);
                    $diffMinutes = abs(\Carbon\Carbon::parse($h->created_at)->diffInMinutes(\Carbon\Carbon::parse($dernier->created_at)));
                    if ($diffMinutes <= 15) {
                        $groupeCourant[] = $h;
                    } else {
                        $groupes[] = $groupeCourant;
                        $groupeCourant = [$h];
                    }
                }
            }
            if (!empty($groupeCourant)) {
                $groupes[] = $groupeCourant;
            }

            // Fallback si pas d'historique mais cache_nombre_revisions > 0
            if (empty($groupes) && ($realisationTache->cache_nombre_revisions > 0)) {
                $nb = (int) $realisationTache->cache_nombre_revisions;
                for ($i = 1; $i <= $nb; $i++) {
                    $groupes[] = [(object)[
                        'created_at' => $realisationTache->updated_at ?? now(),
                        'changement' => $realisationTache->remarques ?? null
                    ]];
                }
            }

            $tentative = 1;
            foreach ($groupes as $grp) {
                $premier = $grp[0];
                $message = null;
                foreach ($grp as $entry) {
                    $msg = $service->extraireMessageFormateur($entry->changement ?? '');
                    if ($msg) {
                        $message = $msg;
                        break;
                    }
                }

                // Vérifier si la tâche a été validée ultérieurement
                $dateDemande = \Carbon\Carbon::parse($premier->created_at);
                $isValide = false;
                $dateValidation = null;

                $histoValidation = HistoriqueRealisationTache::where('realisation_tache_id', $rtId)
                    ->where('created_at', '>', $dateDemande)
                    ->where(function ($q) {
                        $q->where('changement', 'like', '%APPROVED%')
                          ->orWhere('changement', 'like', '%Validée%');
                    })
                    ->first();

                if ($histoValidation) {
                    $isValide = true;
                    $dateValidation = $histoValidation->created_at;
                }

                RevisionTache::create([
                    'reference' => 'REV-RT' . $rtId . '-' . $tentative . '-' . substr(uniqid(), -4),
                    'realisation_tache_id' => $rtId,
                    'tentative' => $tentative,
                    'date_demande' => $dateDemande,
                    'message_formateur' => $message,
                    'date_validation' => $dateValidation,
                    'est_valide' => $isValide,
                ]);

                $totalCrees++;
                $tentative++;
            }

            $service->synchroniserCacheRevisionDepuisRevisionTaches($rtId);
            $totalTachesMisesAJour++;
        }

        $this->info("Rattrapage terminé avec succès !");
        $this->line("- Révisions créées : <info>{$totalCrees}</info>");
        $this->line("- Tâches resynchronisées : <info>{$totalTachesMisesAJour}</info>");

        return Command::SUCCESS;
    }
}
