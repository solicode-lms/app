<?php


namespace Modules\PkgFormation\Models;
use Modules\PkgFormation\Models\Base\BaseFormateur;

class Formateur extends BaseFormateur
{
    public function __toString()
    {
        return ($this->nom ?? "") . " " . $this->prenom ?? "" ;
    }

    public function generateReference(): string
    {
        return $this->matricule;
    }
}
