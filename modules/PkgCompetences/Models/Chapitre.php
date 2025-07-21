<?php


namespace Modules\PkgCompetences\Models;
use Modules\PkgCompetences\Models\Base\BaseChapitre;

class Chapitre extends BaseChapitre
{


    public function getMicroCompetenceIdAttribute()
    {
        return $this->uniteApprentissage
            ? $this->uniteApprentissage->micro_competence_id
            : null;
    }


    public function generateReference(): string
    {
        return $this->uniteApprentissage->microCompetence->competence->module->filiere->reference . "-" . $this->code ;
    }

    public function __toString()
    {
        return ($this->code ?? "") . "-" . ($this->nom ?? "");
    }


}

