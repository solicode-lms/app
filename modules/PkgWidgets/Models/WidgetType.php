<?php


namespace Modules\PkgWidgets\Models;
use Modules\PkgWidgets\Models\Base\BaseWidgetType;

class WidgetType extends BaseWidgetType
{
    public function generateReference(): string
    {
        return $this->type ;
    }

}
