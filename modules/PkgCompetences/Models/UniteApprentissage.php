<?php

namespace Modules\PkgCompetences\Models;
use Modules\PkgCompetences\Models\Base\BaseUniteApprentissage;

class UniteApprentissage extends BaseUniteApprentissage
{
    public function generateReference(): string
    {
        return $this->microCompetence->reference . "-" . $this->code ;
    }

    public function __toString()
    {
        return ($this->code ?? "") . "-" . ($this->nom ?? "");
    }
}
