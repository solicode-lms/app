<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models;
use Modules\Core\Models\Base\BaseSysController;

class SysController extends BaseSysController
{
    public function generateReference(): string
    {
        return $this->slug ;
    }
}
