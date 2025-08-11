<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseEtatRealisationModule;

class EtatRealisationModule extends BaseEtatRealisationModule
{
    public function generateReference(): string
    {
        return  $this->code ?? ""; 
    }
}
