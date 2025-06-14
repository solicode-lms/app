<?php


namespace Modules\PkgApprenants\Models;
use Modules\PkgApprenants\Models\Base\BaseGroupe;
use Modules\PkgFormation\Models\AnneeFormation;

class Groupe extends BaseGroupe
{

    // protected $with = [
    //    'filiere',
    //    'anneeFormation'
    // ];

    public function generateReference(): string
    {
        return $this->code . "-" . $this->anneeFormation->reference;
    }
}
