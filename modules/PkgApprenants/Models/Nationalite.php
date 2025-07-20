<?php


namespace Modules\PkgApprenants\Models;
use Modules\PkgApprenants\Models\Base\BaseNationalite;

class Nationalite extends BaseNationalite
{
    public function generateReference(): string
    {
        return $this->code ;
    }
}
