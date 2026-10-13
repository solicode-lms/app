<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;

class RecalculerGatingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:recalculer-gating {--force : Forcer le recalcul même si les données de cache existent déjà}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Peuple et met à jour les données de cache pour l\'indicateur de validation du prototype au 1er essai (R_gating)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("=== Démarrage du rattrapage des données de Gating (Indicateur 02 — R_gating) ===");

        $isForce = (bool) $this->option('force');

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

        foreach ($projets as $projet) {
            // Si pas force et que les colonnes sont déjà valorisées, on passe
            if (!$isForce && !is_null($projet->cache_est_valide_prototype_premier_essai)) {
                if ($projet->cache_est_valide_prototype_premier_essai === true) {
                    $totalValides1erEssai++;
                    $totalEvalues++;
                } elseif ($projet->cache_est_valide_prototype_premier_essai === false) {
                    $totalEvalues++;
                    if (is_null($projet->cache_date_validation_prototype)) {
                        $totalEnRemediation++;
                    }
                }
                continue;
            }

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

            // Récupération de la tâche prototype principale (la plus avancée ou dernière évaluée)
            $prototypeTache = $tachesPrototype->first();
            $wfCode = optional($prototypeTache->etatRealisationTache?->workflowTache)->code;

            // Détection si la tâche a été évaluée
            if (in_array($wfCode, ['APPROVED', 'NOT_VALIDATED', 'REVISION_NECESSAIRE'])) {
                $totalEvalues++;

                // Analyse de l'historique pour détecter les échecs / rejets préalables
                $rejets = HistoriqueRealisationTache::where('realisation_tache_id', $prototypeTache->id)
                    ->where(function ($q) {
                        $q->where('changement', 'like', '%Non validée%')
                          ->orWhere('changement', 'like', '%À réviser%')
                          ->orWhere('changement', 'like', '%À faire%')
                          ->orWhere('changement', 'like', '%NOT_VALIDATED%');
                    })
                    ->count();

                $estApprouve = ($wfCode === 'APPROVED') || (($prototypeTache->note ?? 0) >= 10);

                if ($estApprouve) {
                    if ($rejets > 0) {
                        // Validé après remédiation (2ème passage ou plus)
                        $tentatives = $rejets + 1;
                        $premierEssai = false;
                        $dateValidation = $prototypeTache->updated_at ?? now();
                    } else {
                        // Validé dès le premier passage
                        $tentatives = 1;
                        $premierEssai = true;
                        $dateValidation = $prototypeTache->updated_at ?? now();
                        $totalValides1erEssai++;
                    }
                } else {
                    // Actuellement en échec / remédiation
                    $tentatives = max(1, $rejets + 1);
                    $premierEssai = false;
                    $dateValidation = null;
                    $totalEnRemediation++;
                }

                $projet->update([
                    'cache_nombre_tentatives_prototype' => $tentatives,
                    'cache_est_valide_prototype_premier_essai' => $premierEssai,
                    'cache_date_validation_prototype' => $dateValidation,
                ]);
                $nbMisesAJour++;
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
        $this->info("Projets mis à jour en BDD : {$nbMisesAJour}");
        $this->info("Prototypes évalués        : {$totalEvalues}");
        $this->info("Validés au 1er passage    : {$totalValides1erEssai}");
        $this->info("Actuellement en remédiation : {$totalEnRemediation}");
        $this->info("Taux global R_gating      : {$tauxGlobal}%");
        $this->info("--------------------------------------------------");
        $this->info("✅ Rattrapage de l'Indicateur 02 terminé avec succès !");

        return Command::SUCCESS;
    }
}
