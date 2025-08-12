<?php

namespace Modules\PkgRealisationProjets\Observers;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationProjets\Models\RealisationProjet;

class RealisationProjetObserver
{
    /**
     * Événement déclenché juste avant la suppression d’un RealisationProjet.
     *
     * 🎯 Objectif : capturer les IDs nécessaires avant suppression,
     *               puis déclencher un traitement différé via JobManager.
     */
    public function deleting(RealisationProjet $realisationProjet): void
    {
        // Préparation des données nécessaires avant la suppression
        $payload = [
            'ua_ids'                    => $realisationProjet->realisationTaches
                                            ->flatMap(fn($t) => $t->realisationUaPrototypes->pluck('realisation_ua_id')
                                                ->merge($t->realisationUaProjets->pluck('realisation_ua_id')))
                                            ->filter()
                                            ->unique()
                                            ->values()
                                            ->all(),
            'realisation_chapitres_ids' => $realisationProjet->realisationTaches
                                            ->flatMap(fn($t) => $t->realisationChapitres->pluck('id'))
                                            ->filter()
                                            ->unique()
                                            ->values()
                                            ->all(),
        ];

        // Lancement du job différé
        JobManager::initJob(
            methodName:     "deletedObserverJob",
            modelName:      "realisationProjet",
            moduleName:     "PkgRealisationProjets",
            id:             $realisationProjet->id,
            changedFields:  [],
            payload:        $payload
        )->dispatchTraitementCrudJob();
    }
}
