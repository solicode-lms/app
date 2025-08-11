<?php


namespace Modules\PkgApprentissage\Observers;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;

class RealisationUaPrototypeObserver
{

    public function created(RealisationUaPrototype $realisationUaPrototype): void
    {
        //
    }

    public function updated(RealisationUaPrototype $realisationUaPrototype): void
    {
        // Champs réellement modifiés
        $changedFields = array_keys($realisationUaPrototype->getDirty());

        JobManager::initJob(
            "updatedObserverJob",
            "realisationUaPrototype",
            "PkgApprentissage", 
            $realisationUaPrototype->id,
            $changedFields
        )->dispatchTraitementCrudJob();
    }


    /**
     * Événement déclenché lors de la suppression d'une RealisationUaPrototype.
     */
    public function deleted(RealisationUaPrototype $realisationUaPrototype): void
    {
       
    }

    /**
     * Handle the RealisationUaPrototype "restored" event.
     */
    public function restored(RealisationUaPrototype $realisationUaPrototype): void
    {
        //
    }

    /**
     * Handle the RealisationUaPrototype "force deleted" event.
     */
    public function forceDeleted(RealisationUaPrototype $realisationUaPrototype): void
    {
        //
    }
}
