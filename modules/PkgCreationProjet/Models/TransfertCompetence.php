<?php


namespace Modules\PkgCreationProjet\Models;
use Modules\PkgCreationProjet\Models\Base\BaseTransfertCompetence;

class TransfertCompetence extends BaseTransfertCompetence
{

     protected $with = [
       'competence',
       'niveauDifficulte',
    ];

    public function __toString()
    {
        return ($this->niveauDifficulte->nom ?? "") . "-" . ($this->competence ?? "")  ;
    }
}
