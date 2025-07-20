<?php


namespace Modules\PkgEvaluateurs\Models;
use Modules\PkgEvaluateurs\Models\Base\BaseEvaluationRealisationProjet;

class EvaluationRealisationProjet extends BaseEvaluationRealisationProjet
{
    //  protected $with = [
    //    'realisationProjet',
    //    'evaluateur',
    //    'etatEvaluationProjet'
    // ];

    public function generateReference(): string
    {
         return  $this->realisationProjet->reference . "-" .  $this->evaluateur->reference;
    }
}
