<?php


namespace Modules\Core\Models;
use Modules\Core\Models\Base\BaseSysColor;

class SysColor extends BaseSysColor
{
     public function generateReference(): string
    {
        return $this->name ;
    }

}
