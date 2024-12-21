<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class GenerateModulePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:generate-modules
                            {--module= : Specify a module to scan routes (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permissions based on routes defined in module files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $moduleOption = $this->option('module');
        $baseModulePath = base_path('Modules');

        if ($moduleOption) {
            $this->generatePermissionsForModule($moduleOption, $baseModulePath);
        } else {
            $modules = File::directories($baseModulePath);
            foreach ($modules as $modulePath) {
                $moduleName = basename($modulePath);
                $this->generatePermissionsForModule($moduleName, $baseModulePath);
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Generate permissions for a specific module.
     *
     * @param string $moduleName
     * @param string $baseModulePath
     */
    protected function generatePermissionsForModule($moduleName, $baseModulePath)
    {
        $routesPath = "{$baseModulePath}/{$moduleName}/Routes";
        if (!File::exists($routesPath)) {
            $this->warn("Module '{$moduleName}' does not have a Routes directory.");
            return;
        }

        $routeFiles = File::allFiles($routesPath);
        foreach ($routeFiles as $routeFile) {
            $this->processRouteFile($routeFile->getPathname());
        }
    }

    /**
     * Process a route file and generate permissions based on its routes.
     *
     * @param string $filePath
     */
    protected function processRouteFile($filePath)
    {
        $this->info("Processing routes from file: {$filePath}");

        Route::middleware('web')->group(function () use ($filePath) {
            require $filePath;
        });

        $routes = Route::getRoutes();
        $permissionsAdded = 0;

        foreach ($routes as $route) {
            $action = $route->getActionName();

            if (strpos($action, '@') !== false) {
                [$controller, $method] = explode('@', class_basename($action));
                $permissionName = strtolower("{$method}-{$controller}");

                if (!Permission::where('name', $permissionName)->exists()) {
                    Permission::create(['name' => $permissionName]);
                    $permissionsAdded++;
                    $this->info("Permission created: {$permissionName}");
                }
            }
        }

        if ($permissionsAdded === 0) {
            $this->info("No new permissions were added.");
        }
    }
}
