<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationUaProjet;

class RealisationUaProjet extends BaseRealisationUaProjet
{

    protected static function booted()
    {
        static::saving(function ($model) {
            // Récupérer la tâche liée
            $tache = \Modules\PkgRealisationTache\Models\RealisationTache::find($model->realisation_tache_id);

            // Récupérer l’état de la tâche et son workflow
            $etat = $tache?->etatRealisationTache;
            $workflow = $etat?->workflowTache;

            if (!$workflow) return;

            // Récupération de l’ordre de référence de IN_PROGRESS
            static $ordreInProgress = null;
            if (is_null($ordreInProgress)) {
                $ordreInProgress = \Modules\PkgRealisationTache\Models\WorkflowTache::where('code', 'IN_PROGRESS')->value('ordre');
            }

            // Affecter date_debut si ordre ≥ IN_PROGRESS
            if (is_null($model->date_debut) && $workflow->ordre >= $ordreInProgress) {
                $model->date_debut = now();
            }

            // Affecter date_fin si code = DONE
            if (is_null($model->date_fin) && $workflow->code === 'APPROVED') {
                $model->date_fin = now();
            }

            // Mettre à jour dernier_update si colonne existante
            if (in_array('dernier_update', $model->getFillable())) {
                $model->dernier_update = now();
            }
        });
    }


}
