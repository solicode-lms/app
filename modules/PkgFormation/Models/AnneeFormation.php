<?php


namespace Modules\PkgFormation\Models;
use Modules\PkgFormation\Models\Base\BaseAnneeFormation;

class AnneeFormation extends BaseAnneeFormation
{
    public function generateReference(): string
    {
        $reference = $this->date_debut->year . "/" . $this->date_fin->year;
        return $reference;
    }

}
