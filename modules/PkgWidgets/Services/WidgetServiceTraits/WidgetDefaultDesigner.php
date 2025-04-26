<?php
namespace Modules\PkgWidgets\Services\WidgetServiceTraits;

use Modules\Core\Services\SysColorService;

trait  WidgetDefaultDesigner
{
    public function defaultDesigner($widget)
    {
        if (empty($widget->sysColor) && !empty($widget->model?->sysColor)) {
            $widget->sysColor = $widget->model->sysColor;
        }

        if (empty($widget->icon) && !empty($widget->model?->icone)) {
            $widget->icon = $widget->model->icone;
        }

        if (!empty($widget->sysColor)) {
            $widget->sysColor->textColor = (new SysColorService())->getTextColorForBackground($widget->sysColor->hex);
        }
    }
}
