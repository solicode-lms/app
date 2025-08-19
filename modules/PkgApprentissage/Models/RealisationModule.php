<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationModule;
use Modules\PkgApprentissage\Models\Traits\LecturePedagogieTrait;

class RealisationModule extends BaseRealisationModule
{

    use LecturePedagogieTrait;
    
    public function __toString()
    {
        return $this->module ?? "";
    }

    public function generateReference(): string
    {
        return  $this->module->reference . "-" . $this->apprenant->reference  ; 
    }
}
