<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Models;
use Modules\PkgFormation\Models\Base\BaseModule;

class Module extends BaseModule
{
    public function __toString()
    {
        return trim(($this->code ?? '') . ' ' . ($this->nom ?? ''));
    }

}
