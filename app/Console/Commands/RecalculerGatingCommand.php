<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Modules\PkgRealisationTache\Models\LiveCoding;
use Modules\PkgRealisationTache\Services\LiveCodingService;

class RecalculerGatingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:recalculer-gating {--force : Forcer le recalcul et la régénération des live codings historiques}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Peuple la table live_codings et met à jour le cache de gating (Indicateur 02 — R_gating)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("=== Démarrage du rattrapage des données de Gating (Indicateur 02 — R_gating) ===");

        $isForce = (bool) $this->option('force');
        $liveCodingService = app(LiveCodingService::class);

        $projets = RealisationProjet::with([
            'realisationTaches.tache.phaseEvaluation',
            'realisationTaches.etatRealisationTache.workflowTache'
        ])
        ->whereHas('affectationProjet.projet', function ($q) {
            $q->sessionsFormation();
        })
        ->get();

        $this->info("Traitement de {$projets->count()} réalisations de projets...");

        $nbMisesAJour = 0;
        $totalEvalues = 0;
        $totalValides1erEssai = 0;
        $totalEnRemediation = 0;
        $totalLiveCodingsCrees = 0;

        foreach ($projets as $projet) {
            // Filtrer les tâches prototypes de Niveau 2 (phaseEvaluation = N2)
            $tachesPrototype = $projet->realisationTaches->filter(fn($rt) => $rt->isPrototype());

            if ($tachesPrototype->isEmpty()) {
                if ($projet->cache_nombre_tentatives_prototype > 0 || !is_null($projet->cache_est_valide_prototype_premier_essai)) {
                    $projet->update([
                        'cache_nombre_tentatives_prototype' => 0,
                        'cache_est_valide_prototype_premier_essai' => null,
                        'cache_date_validation_prototype' => null,
                    ]);
                    $nbMisesAJour++;
                }
                continue;
            }

            $prototypeTache = $tachesPrototype->first();
            $wfCode = optional($prototypeTache->etatRealisationTache?->workflowTache)->code;

            // Détection si la tâche prototype a été évaluée
            if (in_array($wfCode, ['APPROVED', 'NOT_VALIDATED', 'EN_REMEDIATION', 'REVISION_NECESSAIRE'])) {
                // Reconstituer l'historique dans live_codings si vide ou si force
                $existingCount = LiveCoding::where('realisation_tache_id', $prototypeTache->id)->count();

                if ($isForce || $existingCount === 0) {
                    if ($isForce && $existingCount > 0) {
                        LiveCoding::where('realisation_tache_id', $prototypeTache->id)->delete();
                    }

                    // Recherche des échecs / rejets préalables dans l'historique
                    $historiqueRejets = HistoriqueRealisationTache::where('realisation_tache_id', $prototypeTache->id)
                        ->where(function ($q) {
                            $q->where('changement', 'like', '%Non validée%')
                              ->orWhere('changement', 'like', '%NOT_VALIDATED%')
                              ->orWhere('changement', 'like', '%EN_REMEDIATION%')
                              ->orWhere('changement', 'like', '%En remédiation%');
                        })
                        ->orderBy('created_at', 'asc')
                        ->get();

                    $k = 0;
                    foreach ($historiqueRejets as $rejet) {
                        $k++;
                        LiveCoding::create([
                            'reference' => 'LC-RT' . $prototypeTache->id . '-' . $k . '-hist',
                            'realisation_tache_id' => $prototypeTache->id,
                            'tentative' => $k,
                            'est_valide' => false,
                            'date_passage' => $rejet->created_at ?? $prototypeTache->created_at,
                            'commentaire' => 'Tentative ' . $k . ' (Rattrapage historique)',
                        ]);
                        $totalLiveCodingsCrees++;
                    }

                    $estApprouve = ($wfCode === 'APPROVED') || (($prototypeTache->note ?? 0) >= 10);

                    if ($estApprouve) {
                        $k++;
                        LiveCoding::create([
                            'reference' => 'LC-RT' . $prototypeTache->id . '-' . $k . '-val',
                            'realisation_tache_id' => $prototypeTache->id,
                            'tentative' => $k,
                            'est_valide' => true,
                            'date_passage' => $prototypeTache->updated_at ?? now(),
                            'commentaire' => $prototypeTache->remarque_evaluateur ?? 'Validation prototype',
                        ]);
                        $totalLiveCodingsCrees++;
                    } elseif ($k === 0) {
                        // En remédiation / rejet sans trace préalable dans l'historique
                        LiveCoding::create([
                            'reference' => 'LC-RT' . $prototypeTache->id . '-1-eval',
                            'realisation_tache_id' => $prototypeTache->id,
                            'tentative' => 1,
                            'est_valide' => false,
                            'date_passage' => $prototypeTache->updated_at ?? now(),
                            'commentaire' => $prototypeTache->remarque_evaluateur ?? 'Évaluation initiale',
                        ]);
                        $totalLiveCodingsCrees++;
                    }

                    // Synchronisation du cache projet à partir de live_codings
                    $liveCodingService->synchroniserCacheGatingDepuisLiveCodings($prototypeTache);
                    $nbMisesAJour++;
                }

                // Statistiques de sortie basées sur le projet
                $projet->refresh();
                $totalEvalues++;
                if ($projet->cache_est_valide_prototype_premier_essai === true) {
                    $totalValides1erEssai++;
                } elseif ($projet->cache_est_valide_prototype_premier_essai === false) {
                    if (is_null($projet->cache_date_validation_prototype)) {
                        $totalEnRemediation++;
                    }
                }
            } else {
                // Tâche prototype existante mais non encore évaluée
                if ($projet->cache_nombre_tentatives_prototype > 0 || !is_null($projet->cache_est_valide_prototype_premier_essai)) {
                    $projet->update([
                        'cache_nombre_tentatives_prototype' => 0,
                        'cache_est_valide_prototype_premier_essai' => null,
                        'cache_date_validation_prototype' => null,
                    ]);
                    $nbMisesAJour++;
                }
            }
        }

        $tauxGlobal = $totalEvalues > 0 ? round(($totalValides1erEssai / $totalEvalues) * 100, 1) : 0;

        $this->info("--------------------------------------------------");
        $this->info("Projets examinés en BDD    : {$projets->count()}");
        $this->info("Live Codings créés         : {$totalLiveCodingsCrees}");
        $this->info("Projets mis à jour         : {$nbMisesAJour}");
        $this->info("Prototypes évalués         : {$totalEvalues}");
        $this->info("Validés au 1er passage     : {$totalValides1erEssai}");
        $this->info("Actuellement en remédiation: {$totalEnRemediation}");
        $this->info("Taux global R_gating       : {$tauxGlobal}%");
        $this->info("--------------------------------------------------");
        $this->info("✅ Rattrapage de l'Indicateur 02 terminé avec succès !");

        return Command::SUCCESS;
    }
}
