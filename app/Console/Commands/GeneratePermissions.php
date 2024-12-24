<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class GeneratePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permissions automatically based on defined routes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Parcours de toutes les routes définies
        $routes = Route::getRoutes();
        $permissionsAdded = 0;

        foreach ($routes as $route) {
            $action = $route->getActionName();

            // Vérifier si l'action contient un contrôleur et une méthode
            if (strpos($action, '@') !== false) {
                [$controller, $method] = explode('@', class_basename($action));
                $permissionName = strtolower("{$method}-{$controller}");

                // Créer la permission si elle n'existe pas
                if (!Permission::where('name', $permissionName)->exists()) {
                  //  Permission::create(['name' => $permissionName]);
                    $permissionsAdded++;
                    $this->info("Permission créée : {$permissionName}");
                }
            }
        }

        // Résumé des permissions ajoutées
        if ($permissionsAdded > 0) {
            $this->info("Un total de {$permissionsAdded} permissions ont été créées.");
        } else {
            $this->info("Aucune nouvelle permission n'a été créée. Toutes existent déjà.");
        }

        return Command::SUCCESS;
    }
}
