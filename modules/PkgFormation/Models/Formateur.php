<?php


namespace Modules\PkgFormation\Models;
use Modules\PkgFormation\Models\Base\BaseFormateur;

class Formateur extends BaseFormateur
{

    // protected $with = [
    //     'user'
    // ];

    public function __toString()
    {
        return ($this->nom ?? "") . " " . $this->prenom ?? "" ;
    }

    public function generateReference(): string
    {
        return $this->matricule;
    }

    /**
     * Un Formateur a exactement un Evaluateur.
     */
    public function evaluateur()
    {
        return $this->hasOne(\Modules\PkgValidationProjets\Models\Evaluateur::class);
    }
}
