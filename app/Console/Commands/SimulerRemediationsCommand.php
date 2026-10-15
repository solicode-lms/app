<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgTableauBord\Models\Remediation;
use Modules\PkgTableauBord\Services\TableauBordService;

class SimulerRemediationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:simuler-remediations 
                            {--all : Traiter toutes les affectations au lieu de la dernière}
                            {--affectation_id= : ID spécifique de l\'affectation de projet à traiter}
                            {--inserer-remediations : Insérer des remédiations réelles pour les tâches passées en remédiation sans fiche}
                            {--reset : Réinitialiser les remédiations et dates}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyse les données réelles BDD (historique Live Coding, seuil 30 min) et synchronise l\'état "En remédiation" ainsi que la table remediations.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== Analyse et Synchronisation des Données Réelles de Remédiation (Indicateur 06 — T_intervention) ===");

        $tableauBordService = app(TableauBordService::class);

        // 1. Sélection des affectations
        $affId = $this->option('affectation_id');
        $queryAff = AffectationProjet::with(['projet.formateur', 'groupe', 'realisationProjets.apprenant']);

        if ($this->option('all')) {
            $affectations = $queryAff->get();
        } elseif ($affId) {
            $affectation = $queryAff->find($affId);
            $affectations = $affectation ? collect([$affectation]) : collect();
        } else {
            $affectation = (clone $queryAff)
                ->whereHas('realisationProjets.realisationTaches', fn($q) => $q->where('is_live_coding', 1))
                ->latest('id')
                ->first()
                ?? (clone $queryAff)->whereNotNull('date_fin_live_coding_collectif')->latest('date_fin_live_coding_collectif')->first()
                ?? $queryAff->latest('date_debut')->first();
            $affectations = $affectation ? collect([$affectation]) : collect();
        }

        if ($affectations->isEmpty()) {
            $this->error("Aucune affectation de projet trouvée dans la base de données.");
            return 1;
        }

        // Option Reset
        if ($this->option('reset')) {
            if (Schema::hasTable('remediations')) {
                $deleted = DB::table('remediations')->where('reference', 'LIKE', 'REM_%')->delete();
                $this->info("Remédiations supprimées ({$deleted} lignes).");
            }
            if (Schema::hasColumn('affectation_projets', 'date_fin_live_coding_collectif')) {
                $ids = $affectations->pluck('id');
                DB::table('affectation_projets')->whereIn('id', $ids)->update([
                    'date_fin_live_coding_collectif' => null,
                ]);
                $this->info("Date de fin de Live Coding collectif réinitialisée pour " . $ids->count() . " affectation(s).");
            }
            return 0;
        }

        foreach ($affectations as $affectation) {
            $nomGroupe = $affectation->groupe->code ?? ($affectation->groupe->nom ?? 'N/A');
            $titreProjet = $affectation->projet->titre ?? 'N/A';
            $this->newLine();
            $this->info("--- Affectation ID [{$affectation->id}] - Groupe [{$nomGroupe}] - Projet [{$titreProjet}] ---");

            // 2. Détermination de la date réelle de fin Live Coding (t_0)
            $dateFin = $tableauBordService->determinerDateFinLiveCodingCollectif($affectation);
            $seuilMinutes = (int) ($affectation->seuil_blocage_minutes ?? 30);

            if ($dateFin) {
                $this->info("Date fin Live Coding réelle trouvée dans l'historique : {$dateFin->format('Y-m-d H:i:s')} (seuil d'autonomie : {$seuilMinutes} min).");
            } else {
                $this->warn("Aucun événement réel 'À valider Live coding' ou 'Validée' trouvé dans l'historique pour cette affectation.");
            }

            // 3. Synchronisation automatique des tâches retardataires vers l'état 'En remédiation'
            $basculees = $tableauBordService->synchroniserApprenantsEnRemediation($affectation, $seuilMinutes);
            if (!empty($basculees)) {
                $this->info("Bascule automatique en état 'En remédiation' de " . count($basculees) . " apprenant(s) ayant dépassé les {$seuilMinutes} min :");
                foreach ($basculees as $b) {
                    $this->line(" - Apprenant: {$b['nom']} | Tâche: {$b['tache_titre']} (ID: {$b['tache_id']})");
                }
            } else {
                $this->info("Aucun apprenant à basculer (soit séance en cours, soit tâches déjà en remédiation ou validées).");
            }

            // 4. Analyse des tâches en remédiation et de la table remediations
            $formateur = $affectation->projet->formateur ?? Formateur::first();
            $formateurId = $formateur ? $formateur->id : 1;

            $rtsEnRemediation = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectation->id))
                ->whereHas('etatRealisationTache.workflowTache', fn($q) => $q->where('code', 'EN_REMEDIATION'))
                ->with(['tache', 'realisationProjet.apprenant'])
                ->get();

            $this->info("Total tâches actuellement en état 'En remédiation' : " . $rtsEnRemediation->count());

            $nbRemediationsExistantes = DB::table('remediations')
                ->whereIn('realisation_tache_id', $rtsEnRemediation->pluck('id'))
                ->count();

            $this->info("Fiches d'étayage existantes dans la table 'remediations' : {$nbRemediationsExistantes}");

            // Option d'insertion des fiches de remédiation réelles manquantes
            if ($this->option('inserer-remediations') || $nbRemediationsExistantes === 0) {
                $nbCreees = 0;
                foreach ($rtsEnRemediation as $idx => $rt) {
                    $exists = DB::table('remediations')->where('realisation_tache_id', $rt->id)->exists();
                    if (!$exists) {
                        $type = ($idx % 2 === 0) ? Remediation::TYPE_TRINOME_SOUTIEN : Remediation::TYPE_INDIVIDUELLE;
                        $tableauBordService->enregistrerRemediation(
                            $rt->id,
                            $formateurId,
                            $type,
                            "Accompagnement et déblocage didactique post-Live Coding collectif."
                        );
                        $nbCreees++;
                    }
                }
                if ($nbCreees > 0) {
                    $this->info("{$nbCreees} fiche(s) d'étayage enregistrée(s) dans la table 'remediations'.");
                }
            }

            // 5. Calcul des Indicateurs d'Autonomie et de Résolution
            $kpi = $tableauBordService->getIndicateurInterventionFormateur($affectation);
            $this->table(
                ['Métrique', 'Valeur'],
                [
                    ['Taux d\'Autonomie Initiale', $kpi['taux_autonomie_formate'] ?? $kpi['taux_intervention_formate']],
                    ['Taux de Résolution des Remédiations', $kpi['taux_resolution_formate'] ?? '100%'],
                    ['Apprenants 100% autonomes (sans remédiation)', $kpi['total_autonomes'] . ' / ' . $kpi['total_apprenants']],
                    ['Total ayant nécessité remédiation (tracés dans remediations)', $kpi['total_remedies']],
                    ['Remédiations résolues & validées (est_resolu = 1)', $kpi['total_resolus'] ?? 0],
                    ['Blocages actifs en attente d\'aide (est_resolu = 0)', $kpi['total_bloques']],
                    ['Zone Didactique', $kpi['badge_label']],
                ]
            );
        }

        return 0;
    }
}
