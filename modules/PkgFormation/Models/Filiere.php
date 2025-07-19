<?php


namespace Modules\PkgFormation\Models;
use Modules\PkgFormation\Models\Base\BaseFiliere;

class Filiere extends BaseFiliere
{
    public function generateReference(): string
    {
        return $this->code;
    }

     public function __toString()
    {
        return trim(($this->code ?? '') . '-' . ($this->nom ?? ''));
    }
}
