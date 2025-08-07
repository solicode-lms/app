<?php


namespace Modules\PkgRealisationTache\Observers;

use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Services\TacheAffectationService;

class RealisationTacheObserver
{
    /**
     * Handle the RealisationTache "created" event.
     */
    public function created(RealisationTache $realisationTache): void
    {
        //
    }

    /**
     * Handle the RealisationTache "updated" event.
     */
    public function updated(RealisationTache $realisationTache): void
    {
        $realisationProjetService = app(RealisationProjetService::class);
        $tacheAffectationService = app(TacheAffectationService::class);

        $realisationProjet = $realisationTache->realisationProjet;
        $tacheAffectation = $realisationTache->tacheAffectation;

        // Si changement d'état → mettre à jour progression et état global
        if ($realisationTache->isDirty('etat_realisation_tache_id')) {
            if ($realisationProjet) {
                $realisationProjetService->mettreAJourEtatDepuisRealisationTaches($realisationProjet);
                $realisationProjetService->mettreAJourProgressionDepuisEtatDesTaches($realisationProjet);
            }

            if ($tacheAffectation) {
                $tacheAffectationService->mettreAjourTacheProgression($tacheAffectation);
                $tacheAffectationService->lancerLiveCodingSiEligible($tacheAffectation);
                
            }
        }

        // Si changement de note → recalcul de la note globale
        if ($realisationTache->isDirty('note')) {
            if ($realisationProjet) {
                $realisationProjetService->calculerNoteEtBaremeDepuisTaches($realisationProjet);
            }
        }
    }
    /**
     * Handle the RealisationTache "deleted" event.
     */
    public function deleted(RealisationTache $realisationTache): void
    {
        //
    }

    /**
     * Handle the RealisationTache "restored" event.
     */
    public function restored(RealisationTache $realisationTache): void
    {
        //
    }

    /**
     * Handle the RealisationTache "force deleted" event.
     */
    public function forceDeleted(RealisationTache $realisationTache): void
    {
        //
    }
}
