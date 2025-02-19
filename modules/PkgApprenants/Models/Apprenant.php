<?php


namespace Modules\PkgApprenants\Models;
use Modules\PkgApprenants\Models\Base\BaseApprenant;

class Apprenant extends BaseApprenant
{

    public function getFormateurId()
    {
        return optional($this->realisationProjets->first()?->affectationProjet?->projet?->formateur)->id;
    }

}
