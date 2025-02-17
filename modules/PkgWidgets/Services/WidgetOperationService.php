<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Services;
use Modules\PkgWidgets\Services\Base\BaseWidgetOperationService;

/**
 * Classe WidgetOperationService pour gérer la persistance de l'entité WidgetOperation.
 */
class WidgetOperationService extends BaseWidgetOperationService
{
    public function dataCalcul($widgetOperation)
    {
        // En Cas d'édit
        if(isset($widgetOperation->id)){
          
        }
      
        return $widgetOperation;
    }
   
}
