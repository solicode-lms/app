<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Services;
use Modules\PkgWidgets\Services\Base\BaseWidgetTypeService;

/**
 * Classe WidgetTypeService pour gérer la persistance de l'entité WidgetType.
 */
class WidgetTypeService extends BaseWidgetTypeService
{
    public function dataCalcul($widgetType)
    {
        // En Cas d'édit
        if(isset($widgetType->id)){
          
        }
      
        return $widgetType;
    }
   
}
