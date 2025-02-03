<?php
// Charger dynamiquement les modèles

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Database\Seeders\SysModuleSeeder;
use Modules\Core\Models\Feature;
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Models\SysController;
use Modules\Core\Models\SysModel;
use Modules\Core\Models\SysModule;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class SysModelSeeder extends Seeder
{
    public static int $order = 29;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // Ajouter les données à partir d'un fichier CSV
        // Charger dynamiquement les modèles
        $this->seedFromModels();

        // Ajouter le contrôleur, le domaine, les fonctionnalités et leurs permissions
        $this->addDefaultControllerDomainFeatures();

        // Associer les permissions aux rôles
        $this->assignPermissionsToRoles($AdminRole, $MembreRole);
    }

    public function seedFromModels(): void
    {
        // Répertoire contenant vos modèles
        $directories = [
            base_path('modules'), // Inclure les modules si vous utilisez des modules.
        ];
    
        foreach ($directories as $directory) {
            $files = $this->getPhpFiles($directory);
    
            foreach ($files as $file) {
                $className = $this->getClassFromFile($file);
    
                if ($className && is_subclass_of($className, \Illuminate\Database\Eloquent\Model::class) && !(new \ReflectionClass($className))->isAbstract()) {
                    SysModel::updateOrCreate(
                        ['model' => $className],
                        [
                            'name' => class_basename($className),
                            'description' => "Automatically added for model $className",
                            'sys_module_id' =>$this->getModuleIdForModel($className), // Vous pouvez définir une logique pour le module_id.
                        ]
                    );
                }
            }
        }
    }
    
    /**
     * Obtenir les fichiers PHP d'un répertoire.
     */
    private function getPhpFiles(string $directory): array
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        $files = [];
    
        foreach ($rii as $file) {
            if ($file->isDir()) {
                continue;
            }
            if ($file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
    
        return $files;
    }
    
    /**
     * Extraire le nom de la classe à partir d'un fichier.
     */
    private function getClassFromFile(string $file): ?string
    {
        $contents = file_get_contents($file);
        $namespace = null;
        $class = null;
    
        if (preg_match('/namespace\s+(.+?);/', $contents, $matches)) {
            $namespace = $matches[1];
        }
    
        if (preg_match('/class\s+([^\s]+)/', $contents, $matches)) {
            $class = $matches[1];
        }
    
        if ($namespace && $class) {
            return "$namespace\\$class";
        }
    
        return null;
    }
    
    /**
     * Obtenir le module_id pour un modèle (personnalisable).
     */
    private function getModuleIdForModel(string $model): ?int
    {
        // Logique pour associer un modèle à un module.
        // Par exemple, en fonction du namespace ou d'autres conventions.
        if (Str::startsWith($model, 'Modules\\')) {
            $moduleName = explode('\\', $model)[1];
            $sysModule = SysModule::where('slug', Str::slug($moduleName))->first();
    
            return $sysModule?->id;
        }
    
        return null;
    }
    



    private function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'Core'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'SysModelController';
        $controllerBaseName = 'sysModel';
        $domainName = 'SysModel';

        // Permissions spécifiques pour chaque type de fonctionnalité
        $featurePermissions = [
            'Édition ' => [ 'create','store','edit','update','destroy','getSysModels'],
            'Lecture' => ['index', 'show'],
            'Extraction' => ['import', 'export'],
        ];

        // Ajouter le contrôleur
        $sysController = SysController::firstOrCreate(
            ['name' => $controllerName],
            [
                'slug' => Str::slug($controllerName),
                'description' => "Controller for $domainName",
                'sys_module_id' => $sysModule->id,
            ]
        );

        // Ajouter le domaine
        $featureDomain = FeatureDomain::firstOrCreate(
            ['slug' => Str::slug($domainName)],
            [
                'name' => $domainName,
                'description' => "Gestion des $domainName",
                'sys_module_id' => $sysModule->id, // ID dynamique du module
                
            ]
        );

        // Ajouter les fonctionnalités principales
        foreach ($featurePermissions as $featureName => $actions) {
            $feature = Feature::firstOrCreate(
                ['name' => "$domainName - $featureName"],
                [
                    'description' => "Feature $featureName for $domainName",
                    'feature_domain_id' => $featureDomain->id,
                ]
            );

            // Ajouter les Permissions liées uniquement à la Feature
            $permissionIds = [];
            foreach ($actions as $action) {
                $permission = Permission::firstOrCreate(
                    ['name' => "$action-$controllerBaseName"],
                    [
                        'guard_name' => 'web',
                        'controller_id' => $sysController->id,
                    ]
                );

                // Collecter les IDs des Permissions pour les associer à la Feature
                $permissionIds[] = $permission->id;
            }

            // Associer les Permissions à la Feature via la table pivot
            $feature->permissions()->syncWithoutDetaching($permissionIds);
        }
    }

    private function assignPermissionsToRoles(string $AdminRole, string $MembreRole): void
    {
        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        // Permissions pour l'administrateur (toutes les permissions du module)
        $adminPermissions = Permission::pluck('name')->toArray();

        // Permissions pour le membre (lecture seule)
        $memberPermissions = Permission::whereIn('name', [
            'index-sysModel',
            'show-sysModel',
        ])->pluck('name')->toArray();

        // Associer les permissions aux rôles
        $admin->givePermissionTo($adminPermissions);
        $membre->givePermissionTo($memberPermissions);
    }
}
