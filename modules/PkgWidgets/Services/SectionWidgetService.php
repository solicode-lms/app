<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Services;
use Modules\PkgWidgets\Services\Base\BaseSectionWidgetService;

/**
 * Classe SectionWidgetService pour gérer la persistance de l'entité SectionWidget.
 */
class SectionWidgetService extends BaseSectionWidgetService
{
    public function dataCalcul($sectionWidget)
    {
        // En Cas d'édit
        if(isset($sectionWidget->id)){
          
        }
      
        return $sectionWidget;
    }
   
}
