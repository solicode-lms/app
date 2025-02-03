<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class DynamicMenuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Partager les menus dynamiques avec toutes les vues
        View::composer('*', function ($view) {
            $view->with('dynamicMenus', $this->loadDynamicMenus());
        });
    }

    private function loadDynamicMenus(): array
    {
        $menuItems = [];
        $modulesPath = base_path('modules');
        $modules = File::directories($modulesPath);

        foreach ($modules as $module) {
            $menuPath = $module . '/resources/views/layouts/_sidebar.blade.php';

            if (File::exists($menuPath)) {
                $moduleName = basename($module);
                $viewPath = $moduleName . '::layouts._sidebar';

                // Stocker la vue (pas de render() ici pour éviter les problèmes de traduction)
                $menuItems[] = $viewPath;
            }
        }

        return $menuItems;
    }
}
