<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationUaPrototype;

class RealisationUaPrototype extends BaseRealisationUaPrototype
{

    protected static function booted()
    {
        static::saving(function ($model) {
            // Charger la tâche liée
            $tache = $model->realisationTache ?: \Modules\PkgRealisationTache\Models\RealisationTache::find($model->realisation_tache_id);

            // Récupérer l’état actuel de la tâche (et son workflow)
            $etat = $tache?->etatRealisationTache;
            $workflow = $etat?->workflowTache;

            if (!$workflow) return;

            // Récupérer une seule fois l’ordre de IN_PROGRESS (code = IN_PROGRESS dans table workflow_taches)
            static $ordreInProgress = null;
            if (is_null($ordreInProgress)) {
                $ordreInProgress = \Modules\PkgRealisationTache\Models\WorkflowTache::where('code', 'IN_PROGRESS')->value('ordre');
            }

            // Affecter date_debut si ordre de workflow ≥ IN_PROGRESS
            if (is_null($model->date_debut) && $workflow->ordre >= $ordreInProgress) {
                $model->date_debut = now();
            }

            // Affecter date_fin si code de workflow = DONE
            if (is_null($model->date_fin) && $workflow->code === 'DONE') {
                $model->date_fin = now();
            }

            // Toujours mettre à jour dernier_update si colonne présente
            if (in_array('dernier_update', $model->getFillable())) {
                $model->dernier_update = now();
            }
        });
    }

}
