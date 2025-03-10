<?php


namespace Modules\PkgApprenants\Models;
use Modules\PkgApprenants\Models\Base\BaseApprenant;
use Modules\PkgGestionTaches\Models\RealisationTache;

class Apprenant extends BaseApprenant
{

  

    public function getFormateurId()
    {
        return optional($this->realisationProjets->first()?->affectationProjet?->projet?->formateur)->id;
    }

    public function __toString()
    {
        return ($this->nom ?? "") . " " . $this->prenom ?? "";
    }

}
