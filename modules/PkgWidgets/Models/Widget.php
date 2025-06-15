<?php


namespace Modules\PkgWidgets\Models;
use Modules\PkgWidgets\Models\Base\BaseWidget;

class Widget extends BaseWidget
{

     protected $with = [
        'sysColor',
    ];

    public function __toString()
    {
        return ($this->label ?? ($this->name ?? ""));
    }
}
