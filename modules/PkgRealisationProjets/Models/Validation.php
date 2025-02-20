<?php


namespace Modules\PkgRealisationProjets\Models;
use Modules\PkgRealisationProjets\Models\Base\BaseValidation;

class Validation extends BaseValidation
{
    public function __toString()
    {
        return ($this->transfertCompetence?->competence ?? "")  . ($this->note ?  (":" .  $this->note . "/" . $this->transfertCompetence?->note ) :  "") ;
    }
}
