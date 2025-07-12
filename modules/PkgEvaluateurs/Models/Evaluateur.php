<?php


namespace Modules\PkgEvaluateurs\Models;
use Modules\PkgEvaluateurs\Models\Base\BaseEvaluateur;

class Evaluateur extends BaseEvaluateur
{
    protected $with = [
        'user'
    ];
}
