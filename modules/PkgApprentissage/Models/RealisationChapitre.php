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
          // État actuel (nouvellement affecté)
          $etatActuel = $model->etatRealisationChapitre ?: EtatRealisationChapitre::find($model->etat_realisation_chapitre_id);

          if (!$etatActuel) return;

          // Récupération de l’ordre de l’état IN_PROGRESS
          static $ordreInProgress = null;
          if (is_null($ordreInProgress)) {
              $ordreInProgress = EtatRealisationChapitre::where('code', 'IN_PROGRESS')->value('ordre');
          }


          $toOrdre = $etatActuel->ordre;

          if (is_null($model->date_debut) && $toOrdre >= $ordreInProgress) {
              $model->date_debut = now();
          }

          // Affecter date_fin uniquement si on passe vers DONE
          if (is_null($model->date_fin) && $etatActuel->code === 'DONE') {
              $model->date_fin = now();
          }
      });
  }

}
