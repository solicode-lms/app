<?php


namespace Modules\PkgEvaluateurs\Models;
use Modules\PkgEvaluateurs\Models\Base\BaseEvaluateur;

class Evaluateur extends BaseEvaluateur
{
    protected $with = [
        'user'
    ];


    // [2026-06-11 11:26:41] developpement.ERROR: Attempt to read property "reference" on null {"userId":1,"exception":"[object] (ErrorException(code: 0): Attempt to read property \"reference\" on null at C:\\AppServer\\solicode-lms\\modules\\PkgEvaluateurs\\Models\\Evaluateur.php:15)
    // public function generateReference(): string
    // {
    //      return  $this->user->reference ;
    // }
}
