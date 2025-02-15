<?php

namespace Modules\PkgRealisationProjets\Models;
use Modules\PkgRealisationProjets\Models\Base\BaseAffectationProjet;

class AffectationProjet extends BaseAffectationProjet
{

    public function __toString()
    {
        return  ($this->projet ?? "") . " [" . ($this->groupe ?? "") . "]" ;
    }
}
