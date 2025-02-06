<?php


namespace Modules\PkgCompetences\Models;
use Modules\PkgCompetences\Models\Base\BaseCompetence;

class Competence extends BaseCompetence
{

    public function __toString()
    {
        return $this->code . "-" .$this->nom;
    }
}
