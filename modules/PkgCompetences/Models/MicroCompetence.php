<?php

namespace Modules\PkgCompetences\Models;
use Modules\PkgCompetences\Models\Base\BaseMicroCompetence;

class MicroCompetence extends BaseMicroCompetence
{
    public function generateReference(): string
    {
        return $this->competence->reference . "-" . $this->code ;
    }

     public function __toString()
    {
        return ($this->code ?? "") . "-" . ($this->titre ?? "");
    }
}
