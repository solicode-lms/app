<?php


namespace Modules\PkgSessions\Models;
use Modules\PkgSessions\Models\Base\BaseAlignementUa;

class AlignementUa extends BaseAlignementUa
{

    public function generateReference(): string
    {
        return  $this->sessionFormation->reference . '-' .  $this->uniteApprentissage->reference ;
    }

    public function __toString()
    {
        return $this->uniteApprentissage ?? "";
    }
}
