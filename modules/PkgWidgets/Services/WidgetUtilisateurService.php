<?php
 

namespace Modules\PkgWidgets\Services;

use Illuminate\Support\Facades\Auth;
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

    /**
 * Récupérer les widgets associés à l'utilisateur actuellement connecté.
 *
 * @return \Illuminate\Database\Eloquent\Collection
 */
public function getWidgetUtilisateurOfCurrentUser()
{
    $user = Auth::user();

    return $this->model
    ->where('user_id', $user->id)
    ->where('visible', true)
    ->get();
}
   
}
