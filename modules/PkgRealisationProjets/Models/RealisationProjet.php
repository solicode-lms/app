<?php


namespace Modules\PkgRealisationProjets\Models;
use Modules\PkgRealisationProjets\Models\Base\BaseRealisationProjet;

class RealisationProjet extends BaseRealisationProjet
{

    protected $with = [
       'affectationProjet',
       'apprenant',
       'etatsRealisationProjet'
    ];

    public function __toString()
    {
        return ($this->affectationProjet ?? "") . "-" . ($this->apprenant ?? "") ;
    }

}
