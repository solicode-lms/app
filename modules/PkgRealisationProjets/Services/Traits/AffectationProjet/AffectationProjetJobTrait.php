<?php

namespace Modules\PkgRealisationProjets\Services\Traits\AffectationProjet;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\Services\TacheAffectationService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService;

/**
 * Trait AffectationProjetJobTrait
 * 
 * Gestion des traitements asynchrones (Jobs) pour AffectationProjet.
 */
trait AffectationProjetJobTrait
{
    /**
     * Job exécuté après la création d'une affectation de projet.
     * 
     * Ce job en tâche de fond effectue les opérations lourdes suivantes :
     * 1. Récupère l'affectation et les apprenants cibles (Groupe ou Sous-Groupe).
     * 2. Récupère toutes les tâches du projet modèle.
     * 3. Crée les TacheAffectations pour lier les tâches au projet affecté.
     * 4. Crée une RéalisationProjet pour chaque apprenant.
     * 5. Synchronise les évaluations via EvaluationRealisationProjetService.
     * 6. Met à jour la barre de progression en temps réel via JobManager.
     *
     * @param  int    $id     ID de l'affectation projet
     * @param  string $token  Token de suivi du traitement (pour JobManager)
     * @return string         'done' en cas de succès, 'error' sinon.
     */
    public function createdObserverJob(int $id, string $token): string
    {
        try {
            $jobManager = new JobManager($token);

            // 1) Récupération de l'affectation
            $affectation = $this->find($id);

            if (!$affectation) {
                $jobManager->setError("L'affectation n'existe pas (id={$id}).");
                return 'error';
            }

            // 2) Récupération des apprenants (priorité sous-groupe)
            $apprenants = collect();
            if ($affectation?->sousGroupe) {
                $apprenants = $affectation->sousGroupe->apprenants;
            } elseif ($affectation?->groupe) {
                $apprenants = $affectation->groupe->apprenants;
            }

            if ($apprenants->isEmpty()) {
                $jobManager->setError("Aucun apprenant trouvé pour l'affectation #{$affectation->id}.");
                return 'error';
            }

            // 3) Récupération des tâches du projet pour créaton des TacheAffectations
            $taches = \Modules\PkgCreationTache\Models\Tache::query()
                ->where('projet_id', $affectation->projet_id)
                ->get();

            // 4) Initialisation progression (tâches + apprenants + sync évaluation)
            $total = $taches->count() + $apprenants->count() + 1;
            $jobManager->initProgress($total);

            // 5) Services nécessaires (résolus via le conteneur)
            $tacheAffectationService = app(\Modules\PkgRealisationTache\Services\TacheAffectationService::class);
            $realisationProjetService = app(\Modules\PkgRealisationProjets\Services\RealisationProjetService::class);
            $evaluationService = app(\Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService::class);

            $jobManager->setLabel("Création des tâches affectation");
            // 6) Création des TacheAffectations : Suivi de réalisation de tâche par groupe
            foreach ($taches as $tache) {
                $tacheAffectationService->create([
                    'tache_id' => $tache->id,
                    'affectation_projet_id' => $affectation->id,
                ]);
                $jobManager->tick();
            }


            // 7) Création des RéalisationProjet
            foreach ($apprenants as $apprenant) {

                $jobManager->setLabel("Création de réalisation de projet pour : " . $apprenant);
                $realisationProjetService->create([
                    'apprenant_id' => $apprenant->id,
                    'affectation_projet_id' => $affectation->id,
                    'date_debut' => $affectation->date_debut,
                    'date_fin' => $affectation->date_fin,
                    'rapport' => null,
                    'etats_realisation_projet_id' => null,
                ]);
                $jobManager->tick();
            }



            // 8) Synchronisation des évaluations
            $evaluationService->SyncEvaluationRealisationProjet($affectation);
            $jobManager->tick();

            // 9) Fin OK
            $jobManager->finish(); // progress=100, status=done
            return 'done';

        } catch (\Throwable $e) {


            $this->handleThrowable($e);

            $jobManager->fail(function () use ($id) {
                // Suppression de l'entité si afterCreate
                $this->destroy($id);
            }, true, $e);

            return 'error';
        }
    }


    /**
     * Job exécuté après la suppression d'une affectation de projet (via Observer).
     * 
     * Ce job recalcule les progressions pédagogiques impactées par la suppression :
     * 1. Recalcule la progression des chapitres liés.
     * 2. Recalcule la progression des Unités d'Apprentissage (UA) liées.
     * 
     * Les IDs des éléments à recalculer sont passés dans le payload du JobManager.
     *
     * @param int $id ID de l'entité (supprimée donc non utilisée directement ici).
     * @param string $token Token pour récupérer le payload et suivre la progression.
     * @return void
     */
    public function deletedObserverJob(int $id, string $token): void
    {
        $jobManager = new JobManager($token);
        $payload = $jobManager->getPayload();

        $realisation_chapitres_ids = collect($payload['realisation_chapitres_ids'] ?? []);
        $ua_ids = collect($payload['ua_ids'] ?? []);

        $total = 0;

        if ($realisation_chapitres_ids->isNotEmpty()) {
            $total++;
        }

        $total += $ua_ids->count();

        $jobManager->initProgress($total);

        // 1️⃣ Progression Chapitres
        if ($realisation_chapitres_ids->isNotEmpty()) {
            $jobManager->setLabel("Mise à jour des chapitres");
            app(\Modules\PkgApprentissage\Services\RealisationChapitreService::class)
                ->calculerProgressionDepuisRealisationChapitresIds($realisation_chapitres_ids);
            $jobManager->tick();
        }

        // 2️⃣ Progression des Unités d’Apprentissage
        if ($ua_ids->isNotEmpty()) {
            $uaService = app(\Modules\PkgApprentissage\Services\RealisationUaService::class);
            $uas = \Modules\PkgApprentissage\Models\RealisationUa::whereIn('id', $ua_ids)->get();

            foreach ($uas as $ua) {
                $jobManager->setLabel("Recalcul de la progression pour UA #{$ua}");
                $uaService->calculerProgression($ua);
                $jobManager->tick();
            }
        }

        $jobManager->finish();
    }
}
