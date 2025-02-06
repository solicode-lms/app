<?php


namespace Modules\PkgCreationProjet\Models;
use Modules\PkgCreationProjet\Models\Base\BaseTransfertCompetence;

class TransfertCompetence extends BaseTransfertCompetence
{

    public function __toString()
    {
        return ($this->niveauDifficulte->nom ?? "") . "-" . ($this->competence->code ?? "")  ;
    }
}
