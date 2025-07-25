<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseEtatRealisationMicroCompetence;

class EtatRealisationMicroCompetence extends BaseEtatRealisationMicroCompetence
{
     protected $with = [
        'sysColor'
    ];
}
