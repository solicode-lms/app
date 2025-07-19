<?php


namespace Modules\PkgFormation\Models;
use Modules\PkgFormation\Models\Base\BaseSpecialite;

class Specialite extends BaseSpecialite
{
     public function generateReference(): string
    {
        return $this->nom ;
    }
}
