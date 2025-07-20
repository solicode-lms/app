<?php

namespace Modules\PkgEvaluateurs\Models;
use Modules\PkgEvaluateurs\Models\Base\BaseEvaluationRealisationTache;

class EvaluationRealisationTache extends BaseEvaluationRealisationTache
{
    // protected $with = [
    //    'realisationTache',
    //    'evaluateur',
    //    'evaluationRealisationProjet'
    // ];


        /**
     * Barème max pour la note = note de la tâche liée.
     */
    public function getMaxNote(): float|null
    {
        return $this->realisationTache?->tache?->note !== null
            ? round($this->realisationTache->tache->note, 2)
            : null;
    }

    public function generateReference(): string
    {
         return  $this->realisationTache->reference . "-" .  $this->evaluateur->reference;
    }

}
