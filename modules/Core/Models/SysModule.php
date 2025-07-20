<?php

namespace Modules\Core\Models;
use Modules\Core\Models\Base\BaseSysModule;

class SysModule extends BaseSysModule
{

    public function generateReference(): string
    {
        return $this->slug ;
    }

}
