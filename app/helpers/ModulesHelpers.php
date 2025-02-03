<?php

use Illuminate\Support\Facades\File;

if (!function_exists('base_module_path')) {
    function base_module_path($path = '')
    {
        // TODO : on peut charger le string "modules" depuis config("modules.module_path")
        return base_path('modules') . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}


if (!function_exists('loadDynamicMenus')) {
    /**
     * Charger dynamiquement les menus depuis les modules.
     *
     * @return array Liste des vues de menus.
     */
    function loadDynamicMenus(): array
    {
        $menuItems = [];

        // Chemin des modules
        $modulesPath = base_path('modules');
        $modules = File::directories($modulesPath);

        foreach ($modules as $module) {
            // Construire le chemin du menu pour le module
            $menuPath = $module . '/resources/views/layouts/_sidebar.blade.php';

            if (File::exists($menuPath)) {
                // Extraire le nom du module
                $moduleName = basename($module);

                // Construire le chemin de la vue
                $viewPath = $moduleName . '::layouts._sidebar';

                // Ajouter la vue au tableau des menus
                $menuItems[] = view($viewPath);
            }
        }

        return $menuItems;
    }
}