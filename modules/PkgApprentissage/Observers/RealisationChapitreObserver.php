<?php

namespace Modules\PkgApprentissage\Observers;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationChapitre;

class RealisationChapitreObserver
{
    /**
     * Événement déclenché lors de la mise à jour d'un RealisationChapitre.
     */
    public function updated(RealisationChapitre $realisationChapitre): void
    {
        // Champs réellement modifiés
        $changedFields = array_keys($realisationChapitre->getDirty());

        JobManager::initJob(
            'updatedObserverJob',      // méthode appelée dans RealisationChapitreService
            'realisationChapitre',     // nom du modèle
            'PkgApprentissage',        // nom du module
            $realisationChapitre->id,  // ID de l'entité
            $changedFields             // champs modifiés
        )->dispatchTraitementCrudJob();
    }

    /**
     * Événement déclenché lors de la suppression d'un RealisationChapitre.
     */
    public function deleted(RealisationChapitre $realisationChapitre): void
    {
        //
    }

    public function created(RealisationChapitre $realisationChapitre): void
    {
        //
    }

    public function restored(RealisationChapitre $realisationChapitre): void
    {
        //
    }

    public function forceDeleted(RelaisationChapitre $realisationChapitre): void
    {
        //
    }
}
