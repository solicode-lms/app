<?php


namespace Modules\PkgFormation\Models;
use Modules\PkgFormation\Models\Base\BaseModule;

class Module extends BaseModule
{

     protected $with = [
       'filiere'
    ];

    public function generateReference(): string
    {
        return $this->filiere->reference . "-" . $this->code ;
    }

      public function __toString()
    {
        return $this->code  . "-" . ($this->nom ?? "");
    }
}
