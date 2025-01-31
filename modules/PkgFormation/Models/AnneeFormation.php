<?php


namespace Modules\PkgFormation\Models;

use Carbon\Carbon;
use Modules\PkgFormation\Models\Base\BaseAnneeFormation;

class AnneeFormation extends BaseAnneeFormation
{
    public function generateReference(): string
    {
        $date_debut = Carbon::parse(str_replace('/', '-',$this->date_debut ));
        $date_fin = Carbon::parse(str_replace('/', '-',$this->date_fin ));

        $reference = $date_debut->year . "/" . $date_fin->year;
        return $reference;
    }

}
