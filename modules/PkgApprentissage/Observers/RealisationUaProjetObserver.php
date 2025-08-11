<?php

namespace Modules\PkgApprentissage\Observers;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationUaProjet;

class RealisationUaProjetObserver
{
    public function created(RealisationUaProjet $realisationUaProjet): void
    {
        //
    }

    public function updated(RealisationUaProjet $realisationUaProjet): void
    {
        // Champs réellement modifiés (fiable vs wasChanged)
        $changedFields = array_keys($realisationUaProjet->getDirty());

        JobManager::initJob(
            'updatedObserverJob',      // méthode côté *Service*
            'realisationUaProjet',     // nom logique du modèle côté JobManager
            'PkgApprentissage',        // package
            $realisationUaProjet->id,  // clé
            $changedFields             // diffs
        )->dispatchTraitementCrudJob();
    }

    public function deleted(RealisationUaProjet $realisationUaProjet): void
    {
        //
    }

    public function restored(RealisationUaProjet $realisationUaProjet): void
    {
        //
    }

    public function forceDeleted(RealisationUaProjet $realisationUaProjet): void
    {
        //
    }
}
