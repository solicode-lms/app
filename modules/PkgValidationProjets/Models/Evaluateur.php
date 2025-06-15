<?php


namespace Modules\PkgValidationProjets\Models;
use Modules\PkgValidationProjets\Models\Base\BaseEvaluateur;

class Evaluateur extends BaseEvaluateur
{
    protected $with = [
        'user'
    ];
}
