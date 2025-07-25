<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationChapitre;

class RealisationChapitre extends BaseRealisationChapitre
{

     protected $with = [
       'chapitre',
       'realisationUa',
       'realisationTache',
       'etatRealisationChapitre'
    ];
}
