<?php


namespace Modules\PkgSessions\Models;
use Modules\PkgSessions\Models\Base\BaseAlignementUa;

class AlignementUa extends BaseAlignementUa
{

    public function generateReference(): string
    {
        return  $this->uniteApprentissage->reference . '-' . $this->sessionFormation->reference ;
    }

    public function __toString()
    {
        return $this->uniteApprentissage ?? "";
    }
}
