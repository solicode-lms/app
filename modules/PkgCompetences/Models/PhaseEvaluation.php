<?php


namespace Modules\PkgCompetences\Models;
use Modules\PkgCompetences\Models\Base\BasePhaseEvaluation;

class PhaseEvaluation extends BasePhaseEvaluation
{

    public function generateReference(): string
    {
        return $this->code;
    }

    public function __toString()
    {
        return ($this->code ?? "") . "-" . ($this->libelle ?? "");
    }
}
