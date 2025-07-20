<?php


namespace Modules\PkgWidgets\Models;
use Modules\PkgWidgets\Models\Base\BaseSectionWidget;

class SectionWidget extends BaseSectionWidget
{
      public function generateReference(): string
    {
        return $this->titre ;
    }

}
