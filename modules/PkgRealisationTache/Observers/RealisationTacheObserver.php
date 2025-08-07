<?php


namespace Modules\PkgRealisationTache\Observers;

use Modules\PkgRealisationTache\Models\RealisationTache;

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
        $a = 0;
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
