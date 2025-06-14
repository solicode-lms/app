<?php


namespace Modules\PkgCompetences\Models;
use Modules\PkgCompetences\Models\Base\BaseCompetence;

class Competence extends BaseCompetence
{

    protected $with = [
        'module'
    ];


    public function __toString()
    {
        return $this->code . "-" . ($this->mini_code ? $this->mini_code : $this->nom);
    }
}
