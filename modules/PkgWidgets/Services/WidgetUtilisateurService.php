<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Services;
use Modules\PkgWidgets\Services\Base\BaseWidgetUtilisateurService;

/**
 * Classe WidgetUtilisateurService pour gérer la persistance de l'entité WidgetUtilisateur.
 */
class WidgetUtilisateurService extends BaseWidgetUtilisateurService
{
    public function dataCalcul($widgetUtilisateur)
    {
        // En Cas d'édit
        if(isset($widgetUtilisateur->id)){
          
        }
      
        return $widgetUtilisateur;
    }
   
}
