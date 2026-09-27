<?php

namespace Modules\PkgQcm\Observers;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\Models\RealisationQcm;

class RealisationQcmObserver
{
    public function created(RealisationQcm $realisationQcm): void
    {
        //
    }

    public function updated(RealisationQcm $realisationQcm): void
    {
        $changedFields = array_keys($realisationQcm->getDirty());

        JobManager::initJob(
            "updatedObserverJob",
            "realisationQcm",
            "PkgQcm", 
            $realisationQcm->id,
            $changedFields
        )->dispatchTraitementCrudJob();
    }

    public function deleted(RealisationQcm $realisationQcm): void
    {
        //
    }

    public function restored(RealisationQcm $realisationQcm): void
    {
        //
    }

    public function forceDeleted(RealisationQcm $realisationQcm): void
    {
        //
    }
}
