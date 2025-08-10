<?php


namespace Modules\PkgRealisationTache\Observers;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
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
        // Récupération des champs modifiés
        $changedFields = array_keys($realisationTache->getDirty());

        $jobManager = JobManager::initJob(
            "updatedObserverJob",
            "realisationTache",
            "PkgRealisationTache", 
            $realisationTache->id,
            $changedFields
        );
        $jobManager->dispatchTraitementCrudJob();
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
