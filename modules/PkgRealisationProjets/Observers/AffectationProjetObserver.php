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
     * Événement déclenché juste avant la suppression d’un AffectationProjet.
     */
    public function deleting(AffectationProjet $affectationProjet): void
    {
        // Charger les relations profondes pour extraire les IDs
        $affectationProjet->load([
            'realisationProjets.realisationTaches.realisationChapitres',
            'realisationProjets.realisationTaches.realisationUaPrototypes',
            'realisationProjets.realisationTaches.realisationUaProjets',
        ]);

        // Collecter tous les realisation_chapitres_ids
        $realisation_chapitres_ids = $affectationProjet->realisationProjets
            ->flatMap(fn($rp) => $rp->realisationTaches)
            ->flatMap(fn($tache) => $tache->realisationChapitres)
            ->pluck('id')
            ->unique()
            ->values()
            ->all();

        // Collecter tous les realisation_ua_ids (prototypes + projets)
        $ua_ids = $affectationProjet->realisationProjets
            ->flatMap(fn($rp) => $rp->realisationTaches)
            ->flatMap(fn($tache) => 
                $tache->realisationUaPrototypes->pluck('realisation_ua_id')
                ->merge($tache->realisationUaProjets->pluck('realisation_ua_id'))
            )
            ->filter()
            ->unique()
            ->values()
            ->all();

        // Payload complet
        $payload = [
            'realisation_chapitres_ids' => $realisation_chapitres_ids,
            'ua_ids' => $ua_ids,
        ];

        JobManager::initJob(
            methodName:     "deletedObserverJob",
            modelName:      "affectationProjet",
            moduleName:     "PkgRealisationProjets",
            id:             $affectationProjet->id,
            changedFields:  [],
            payload:        $payload
        )->dispatchTraitementCrudJob();
    }

}
