<?php


namespace Modules\PkgFormation\Models;
use Modules\PkgFormation\Models\Base\BaseModule;

class Module extends BaseModule
{
    public function generateReference(): string
    {
        return $this->filiere->reference . "." . $this->code ;
    }
}
