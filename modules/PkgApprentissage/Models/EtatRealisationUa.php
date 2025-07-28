<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseEtatRealisationUa;

class EtatRealisationUa extends BaseEtatRealisationUa
{
     protected $with = [
        'sysColor'
    ];

    public function generateReference(): string
    {
        return  $this->code ?? ""; 
    }

}
