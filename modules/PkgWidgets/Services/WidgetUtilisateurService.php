<?php
 

namespace Modules\PkgWidgets\Services;

use Illuminate\Support\Facades\Auth;
use Modules\PkgWidgets\Models\Widget;
use Modules\PkgWidgets\Models\WidgetUtilisateur;
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
          
        }else{
            $widgetUtilisateur->titre = $widgetUtilisateur->widget->name;
            $widgetUtilisateur->sous_titre = $widgetUtilisateur->widget->label;
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
    
        // Étape 1 : Récupérer les widgets liés aux rôles de l'utilisateur
        $roleWidgets = Widget::whereHas('roles', function ($query) use ($user) {
            $query->whereIn('roles.id', $user->roles->pluck('id'));
        })->get();
    
        // Étape 2 : Créer les enregistrements manquants dans widget_utilisateur
        foreach ($roleWidgets as $widget) {
            WidgetUtilisateur::firstOrCreate([
                'user_id' => $user->id,
                'widget_id' => $widget->id
            ], [
                'visible' => true,
                'ordre' => 1,
                'titre' => $widget->name,
                'sous_titre' => $widget->label
            ]);
        }
    
        // Étape 3 : Retourner les widgets visibles de l'utilisateur
        return $this->model
            ->where('user_id', $user->id)
            ->where('visible', true)
            ->get();
    }
   
    public function createInstance(array $data = []){
        $item = parent::createInstance($data);
        $item->visible = true;
        $item->ordre = 1;
        return $item;
    }
}
