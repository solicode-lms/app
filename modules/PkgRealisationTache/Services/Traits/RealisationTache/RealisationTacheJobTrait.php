<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationUa;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Services\TacheAffectationService;
use Modules\PkgRealisationTache\Models\TacheAffectation;

trait RealisationTacheJobTrait
{
    /**
     * Calcule de progression 
     * @param int $id
     * @param string $token
     * @return void
     */
    public function updatedObserverJob(int $id, string $token): void
    {
        $jobManager = new JobManager($token);
        $changedFields = $jobManager->getChangedFields();

        $realisationTache = $this->find($id);

        // Calculer total
        $total = 0;
        $uaIds = collect();

        $total++; // synchroniserEtatsChapitreDepuisTache

        $realisationTache->load(['realisationUaPrototypes', 'realisationUaProjets']);
        foreach ($realisationTache->realisationUaPrototypes as $proto) {
            if ($proto->realisation_ua_id) {
                $uaIds->push($proto->realisation_ua_id);
            }
        }
        foreach ($realisationTache->realisationUaProjets as $projet) {
            if ($projet->realisation_ua_id) {
                $uaIds->push($projet->realisation_ua_id);
            }
        }
        $uaIds = $uaIds->unique()->filter();

        $total += $uaIds->count(); // progression/note pour chaque UA
        $total += 2; // maj état + progression projet
        $total += 2; // maj progression + live coding sur tacheAffectation

        if ($realisationTache->isDirty('note')) {
            $total++; // calcul note + barème projet
        }
        $jobManager->initProgress($total);


        // Calcule de progression : Chapitre, US
        // le changement de réalisation de tâche peut modifier la progression en 3 cas 
        // - tache N1 = chapitre 
        // - tache N2 = RealisationUaPrototype
        // - tache N3 = RealisationUaProjet
        if ($jobManager->isDirty('etat_realisation_tache_id') || $jobManager->isDirty('note')) {




            // N1 : Calcule de progression et mettre à jour l'état de realisationChapitres
            if (!$realisationTache->realisationChapitres->isEmpty()) {
                $jobManager->setLabel("Synchronisation des états de chapitre");
                $realisationChapitreService = new RealisationChapitreService();
                $realisationChapitreService->calculerProgression($realisationTache);
                $jobManager->tick();
            }

            // N2 et N3 : Calcule de progression des Unité d'apprentissage et ces ascendance
            if ($uaIds->isNotEmpty()) {
                $realisationUaService = new RealisationUaService();
                $uas = RealisationUa::whereIn('id', $uaIds)->get();
                foreach ($uas as $ua) {
                    $jobManager->setLabel("Calcul progression pour UA #{$ua}");
                    $realisationUaService->calculerProgression($ua);
                    $jobManager->tick();
                }
            }
        }


        if ($jobManager->isDirty('etat_realisation_tache_id')) {

            // Calcule de progression de Projet
            $realisationProjetService = app(RealisationProjetService::class);
            if ($realisationTache->realisationProjet) {
                $jobManager->setLabel("Mise à jour état projet");
                $realisationProjetService->mettreAJourEtatDepuisRealisationTaches($realisationTache->realisationProjet);
                $jobManager->tick();

                $jobManager->setLabel("Mise à jour progression projet");
                $realisationProjetService->mettreAJourProgressionDepuisEtatDesTaches($realisationTache->realisationProjet);
                $jobManager->tick();
            }

            // Calcule de progression de Live coding
            $tacheAffectationService = app(TacheAffectationService::class);
            if ($realisationTache->tacheAffectation) {
                $jobManager->setLabel("Mise à jour progression tâche affectation");
                $tacheAffectationService->mettreAjourTacheProgression($realisationTache->tacheAffectation);
                $jobManager->tick();

                $jobManager->setLabel("Lancer live coding si éligible");
                $tacheAffectationService->lancerLiveCodingSiEligible($realisationTache->tacheAffectation);
                $jobManager->tick();
            }
        }

        // Calcule de progression de Projet : Note
        if ($jobManager->isDirty('note') && $realisationTache->realisationProjet) {




            $jobManager->setLabel("Calcul note et barème projet");
            $realisationProjetService = app(RealisationProjetService::class);
            $realisationProjetService->calculerNoteEtBaremeDepuisTaches($realisationTache->realisationProjet);
            $jobManager->tick();
        }

        $jobManager->finish();
    }


    public function deletedObserverJob(int $id, string $token): void
    {
        $jobManager = new JobManager($token);
        $payload = $jobManager->getPayload();

        $uaIds = collect($payload['ua_ids'] ?? []);
        $realisationProjetId = $payload['realisation_projet_id'] ?? null;
        $tacheAffectationId = $payload['tache_affectation_id'] ?? null;
        $realisation_chapitres_ids = collect($payload['realisation_chapitres_ids'] ?? []);

        $total = 0;

        // 1️⃣ Chapitres (N1)
        if ($realisation_chapitres_ids->isNotEmpty()) {
            $total++;
        }

        // 2️⃣ UA (N2 / N3)
        $total += $uaIds->count();

        // 3️⃣ Projet
        if ($realisationProjetId) {
            $total += 2;
        }

        // 4️⃣ Affectation
        if ($tacheAffectationId) {
            $total += 2;
        }

        $jobManager->initProgress($total);

        // 1️⃣ Chapitre (N1)
        if ($realisation_chapitres_ids->isNotEmpty()) {
            $jobManager->setLabel("Mise à jour des chapitres");
            $realisationChapitreService = new RealisationChapitreService();
            $realisationChapitreService->calculerProgressionDepuisRealisationChapitresIds($realisation_chapitres_ids);
            $jobManager->tick();
        }

        // 2️⃣ Unités d'apprentissage (UA)
        if ($uaIds->isNotEmpty()) {
            $realisationUaService = new RealisationUaService();
            $uas = RealisationUa::whereIn('id', $uaIds)->get();
            foreach ($uas as $ua) {
                $jobManager->setLabel("Calcul progression pour UA #{$ua->id}");
                $realisationUaService->calculerProgression($ua);
                $jobManager->tick();
            }
        }

        // 3️⃣ Projet
        if ($realisationProjetId) {
            $realisationProjetService = app(RealisationProjetService::class);
            $realisationProjet = RealisationProjet::find($realisationProjetId);
            if ($realisationProjet) {
                $jobManager->setLabel("Mise à jour état projet");
                $realisationProjetService->mettreAJourEtatDepuisRealisationTaches($realisationProjet);
                $jobManager->tick();

                $jobManager->setLabel("Mise à jour progression projet");
                $realisationProjetService->mettreAJourProgressionDepuisEtatDesTaches($realisationProjet);
                $jobManager->tick();
            }
        }

        // 4️⃣ Affectation
        if ($tacheAffectationId) {
            $tacheAffectationService = app(TacheAffectationService::class);
            $tacheAffectation = TacheAffectation::find($tacheAffectationId);
            if ($tacheAffectation) {
                $jobManager->setLabel("Mise à jour progression tâche affectation");
                $tacheAffectationService->mettreAjourTacheProgression($tacheAffectation);
                $jobManager->tick();

                $jobManager->setLabel("Lancer live coding si éligible");
                $tacheAffectationService->lancerLiveCodingSiEligible($tacheAffectation);
                $jobManager->tick();
            }
        }

        $jobManager->finish();
    }

    public function bulkUpdateJob($token, $realisationTache_ids, $champsCoches, $valeursChamps){
          
        
        $total = count( $realisationTache_ids); 
        $jobManager = new JobManager($token,$total);
      

        foreach ($realisationTache_ids as $id) {
            $realisationTache = $this->find($id);
            $this->authorize('update', $realisationTache);
    
            $allFields = $this->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $valeursChamps[$field]])
                ->toArray();
    
            if (!empty($data)) {
                $this->updateOnlyExistanteAttribute($id, $data);
            }

            $jobManager->tick();
            
        }

        return "done";
    }
}
