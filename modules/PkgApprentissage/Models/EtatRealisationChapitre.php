<?php

namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseEtatRealisationChapitre;

class EtatRealisationChapitre extends BaseEtatRealisationChapitre
{
    protected $with = [
        'sysColor'
    ];


}
