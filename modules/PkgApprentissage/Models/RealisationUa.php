<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationUa;

class RealisationUa extends BaseRealisationUa
{

    protected $with = [
       'realisationMicroCompetence',
       'uniteApprentissage',
       'etatRealisationUa'
    ];


    protected static function booted()
    {
        static::saving(function ($model) {
            // Récupérer l’état actuel (relation ou fallback)
            $etatActuel = $model->etatRealisationUa
                ?: EtatRealisationUa::find($model->etat_realisation_ua_id);

            if (!$etatActuel) return;

            // Récupérer une seule fois l’ordre de l’état IN_PROGRESS
            static $ordreInProgress = null;
            if (is_null($ordreInProgress)) {
                $ordreInProgress = EtatRealisationUa::where('code', 'IN_PROGRESS')->value('ordre');
            }

            // Affecter date_debut si état >= IN_PROGRESS et date_debut null
            if (is_null($model->date_debut) && $etatActuel->ordre >= $ordreInProgress) {
                $model->date_debut = now();
            }

            // Affecter date_fin si état == DONE et date_fin null
            if (is_null($model->date_fin) && $etatActuel->code === 'DONE') {
                $model->date_fin = now();
            }

            // Toujours mettre à jour dernier_update si la colonne existe
            if (in_array('dernier_update', $model->getFillable())) {
                $model->dernier_update = now();
            }
        });
    }



    public function __toString()
    {
        return $this->uniteApprentissage;
    }
}
