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
        return  $this->competence->reference . "-" . $this->apprenant->reference  ; 
    }
}
