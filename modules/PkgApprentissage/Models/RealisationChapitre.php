<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationChapitre;

class RealisationChapitre extends BaseRealisationChapitre
{

     protected $with = [
       'chapitre',
       'realisationUa',
       'realisationTache',
       'etatRealisationChapitre'
    ];


    protected static function booted()
    {
        static::saving(function ($model) {
            // Charger l’état actuel (relation ou par ID direct)
            $etatActuel =  EtatRealisationChapitre::find($model->etat_realisation_chapitre_id);

            if (!$etatActuel) return;

            // Charger l’ordre de référence de l’état IN_PROGRESS une seule fois
            static $ordreInProgress = null;
            if (is_null($ordreInProgress)) {
                $ordreInProgress = EtatRealisationChapitre::where('code', 'IN_PROGRESS')->value('ordre');
            }

            // Affecter date_debut si non définie et état >= IN_PROGRESS
            if (is_null($model->date_debut) && $etatActuel->ordre >= $ordreInProgress) {
                $model->date_debut = now();
            }

            // Affecter date_fin si non définie et état = DONE
            if (is_null($model->date_fin) && $etatActuel->code === 'DONE') {
                $model->date_fin = now();
            }
        });
    }

     public function generateReference(): string
    {
        return  $this->realisationUa->reference . "-" . $this->chapitre->reference  ; 
    }
 

}
