<?php


namespace Modules\PkgWidgets\Models;
use Modules\PkgWidgets\Models\Base\BaseWidgetOperation;

class WidgetOperation extends BaseWidgetOperation
{
    public function __toString()
    {
        return $this->operation ?? "";
    }
}
