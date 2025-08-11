<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationCompetence;

class RealisationCompetence extends BaseRealisationCompetence
{
    public function __toString()
    {
        return $this->competence ?? "";
    }
}
