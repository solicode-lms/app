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
     * Événement déclenché lors de la mise à jour d'une RealisationTache.
     */
    public function updated(RealisationTache $realisationTache): void
    {
        // Champs réellement modifiés
        $changedFields = array_keys($realisationTache->getDirty());

        JobManager::initJob(
            "updatedObserverJob",
            "realisationTache",
            "PkgRealisationTache", 
            $realisationTache->id,
            $changedFields
        )->dispatchTraitementCrudJob();
    }


    /**
     * Événement déclenché lors de la suppression d'une RealisationTache.
     */
    public function deleting(RealisationTache $realisationTache): void
    {

        $payload = [
        'realisation_projet_id' => optional($realisationTache->realisationProjet)->id,
        'tache_affectation_id' => optional($realisationTache->tacheAffectation)->id,
        'realisation_chapitres_ids' => $realisationTache->realisationChapitres->pluck('id')->all(),
        'ua_ids' => $realisationTache->realisationUaPrototypes->pluck('realisation_ua_id')
            ->merge($realisationTache->realisationUaProjets->pluck('realisation_ua_id'))
            ->filter()->unique()->values()->all(),
        ];

        JobManager::initJob(
            "deletedObserverJob",
            "realisationTache",
            "PkgRealisationTache",
            $realisationTache->id,
            [],
            $payload
        )->dispatchTraitementCrudJob();
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
