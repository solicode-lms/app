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
        $originalRoles = $widget->original_roles ?? collect();
        $newRoles = $widget->roles->pluck('id')->sort()->values();

        if (
            !$originalRoles->diff($newRoles)->isEmpty() ||
            !$newRoles->diff($originalRoles)->isEmpty()
        ) {
            // Les rôles ont changé → suppression des WidgetUtilisateurs liés
            $widgetUtilisateurService = new WidgetUtilisateurService();
            $widgetUtilisateurService->deleteAllWidgetUtilisateursByWidgetId($widget->id);
        }
    }
}
