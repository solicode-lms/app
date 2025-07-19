<?php


namespace Modules\PkgCompetences\Models;
use Modules\PkgCompetences\Models\Base\BaseChapitre;

class Chapitre extends BaseChapitre
{
   public function generateReference(): string
    {
        return $this->uniteApprentissage->reference . "-" . $this->code ;
    }
}
