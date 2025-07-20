<?php
 

namespace Modules\PkgEvaluateurs\Models;
use Modules\PkgEvaluateurs\Models\Base\BaseEtatEvaluationProjet;

class EtatEvaluationProjet extends BaseEtatEvaluationProjet
{
    protected $with = [
        'sysColor'
    ];

    public function generateReference(): string
    {
         return  $this->code;
    }
}
