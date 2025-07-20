<?php


namespace Modules\PkgApprenants\Models;
use Modules\PkgApprenants\Models\Base\BaseApprenantKonosy;

class ApprenantKonosy extends BaseApprenantKonosy
{
    public function generateReference(): string
    {
        return $this->MatriculeEtudiant ;
    }

}
