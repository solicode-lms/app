<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationUa;

class RealisationUa extends BaseRealisationUa
{

    protected $with = [
       'realisationMicroCompetence',
       'uniteApprentissage',
       'etatRealisationUa'
    ];

    public function __toString()
    {
        return $this->uniteApprentissage->code . "-" . $this->realisationMicroCompetence->apprenant;
    }
}
