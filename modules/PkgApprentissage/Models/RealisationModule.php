<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationModule;

class RealisationModule extends BaseRealisationModule
{

    public function __toString()
    {
        return $this->module ?? "";
    }

    public function generateReference(): string
    {
        return  $this->module->reference . "-" . $this->apprenant->reference  ; 
    }
}
