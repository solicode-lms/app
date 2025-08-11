<?php

namespace Modules\PkgRealisationProjets\Observers;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationProjets\Models\AffectationProjet;

class AffectationProjetObserver
{
    /**
     * Événement déclenché lors de la création d'une AffectationProjet.
     */
    public function created(AffectationProjet $affectationProjet): void
    {
        $changedFields = array_keys($affectationProjet->getDirty());

        JobManager::initJob(
            'createdObserverJob',    // Méthode dans AffectationProjetService si besoin
            'affectationProjet',     // Nom du modèle
            'PkgRealisationProjets', // Nom du module
            $affectationProjet->id,  // ID de l'entité
            $changedFields
        )->dispatchTraitementCrudJob();
    }

    /**
     * Événement déclenché lors de la mise à jour d'une AffectationProjet.
     */
    public function updated(AffectationProjet $affectationProjet): void
    {
      
    }

    /**
     * Événement déclenché lors de la suppression d'une AffectationProjet.
     */
    public function deleted(AffectationProjet $affectationProjet): void
    {
      
    }

    public function restored(AffectationProjet $affectationProjet): void
    {
        //
    }

    public function forceDeleted(AffectationProjet $affectationProjet): void
    {
        //
    }
}
