<?php

namespace Modules\PkgWidgets\Observers;

use Modules\PkgWidgets\Models\Widget;
use Modules\PkgWidgets\Services\WidgetUtilisateurService;

class WidgetObserver
{
    /**
     * On capture les rôles avant modification
     */
    public function updating(Widget $widget)
    {
        // Sauvegarder les rôles actuels pour comparaison
        $widget->setRelation('original_roles', $widget->roles->pluck('id')->sort()->values());
    }

    /**
     * Après modification, on compare et on agit si besoin
     */
    public function updated(Widget $widget)
    {
        $newRoles = $widget->roles->pluck('id')->sort()->values();

        // 🔹 Supprimer tous les widget_utilisateurs dont le user n'a PAS un des nouveaux rôles
        $widgetUtilisateurService = new WidgetUtilisateurService();
        $widgetUtilisateurService->deleteWidgetUtilisateursNotInRoles(
            $widget->id,
            $newRoles->toArray()
        );

        // 🔹 Ajouter pour les rôles qui viennent d'être ajoutés (si besoin)
        // Les widgetUtilisateur seront ajouter pendant la premiere affichage de tableau de board
    }
}
