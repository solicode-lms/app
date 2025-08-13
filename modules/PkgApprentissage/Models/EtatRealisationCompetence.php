<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseEtatRealisationCompetence;

class EtatRealisationCompetence extends BaseEtatRealisationCompetence
{
     public function generateReference(): string
    {
        return  $this->code ?? ""; 
    }

    public function __toString()
    {
        return $this->nom ?? "";
    }
}
