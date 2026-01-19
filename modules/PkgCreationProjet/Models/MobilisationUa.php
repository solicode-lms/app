<?php

namespace Modules\PkgCreationProjet\Models;
use Modules\PkgCreationProjet\Models\Base\BaseMobilisationUa;

class MobilisationUa extends BaseMobilisationUa
{
    public function __toString()
    {
        return $this->uniteApprentissage->__toString();
    }
}