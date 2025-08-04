<?php
 

namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationMicroCompetence;

class RealisationMicroCompetence extends BaseRealisationMicroCompetence
{

    protected $with = [
        'apprenant',
        'microCompetence',
        'etatRealisationMicroCompetence'
    ];


    protected static function booted()
    {
        static::saving(function ($model) {
            // Charger l’état actuel (relation ou fallback)
            $etatActuel =  EtatRealisationMicroCompetence::find($model->etat_realisation_micro_competence_id);

            if (!$etatActuel) return;

            // Ordre minimal requis pour déclencher le démarrage (IN_PROGRESS)
            static $ordreInProgress = null;
            if (is_null($ordreInProgress)) {
                $ordreInProgress = EtatRealisationMicroCompetence::where('code', 'IN_PROGRESS')->value('ordre');
            }

            // Affecter date_debut si elle est vide et que l’état est >= IN_PROGRESS
            if (is_null($model->date_debut) && $etatActuel->ordre >= $ordreInProgress) {
                $model->date_debut = now();
            }

            // Affecter date_fin si elle est vide et que l’état est DONE
            if (is_null($model->date_fin) && $etatActuel->code === 'DONE') {
                $model->date_fin = now();
            }

            // Toujours mettre à jour dernier_update
            $model->dernier_update = now();
        });
    }


    public function __toString()
    {
        return $this->microCompetence->titre . "-" . $this->apprenant;
    }

}
