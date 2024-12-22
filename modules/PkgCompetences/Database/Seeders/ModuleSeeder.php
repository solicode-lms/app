<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Models\Feature;
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Models\SysController;
use Modules\Core\Models\SysModule;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgCompetences\Models\Module;


class ModuleSeeder extends Seeder
{
    public static int $order = 21;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // Ajouter les données à partir d'un fichier CSV
        $this->seedFromCsv();

        // Ajouter le contrôleur, le domaine, les fonctionnalités et leurs permissions
        $this->addDefaultControllerDomainFeatures();

        // Associer les permissions aux rôles
        $this->assignPermissionsToRoles($AdminRole, $MembreRole);
    }

    private function seedFromCsv(): void
    {
        $csvFile = fopen(base_path("modules/PkgCompetences/Database/data/modules.csv"), "r");
        $firstline = true;

        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Module::create([
                    "nom" => $data[0] ,
                    "description" => $data[1] ,
                    "masse_horaire" => $data[2] ,
                    "filiere_id" => $data[3] 
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }

    private function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgCompetences'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            throw new \Exception("Le module avec le slug '{$moduleSlug}' est introuvable.");
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'ModuleController';
        $controllerBaseName = 'module';
        $domainName = 'Module';

        // Permissions spécifiques pour chaque type de fonctionnalité
        $featurePermissions = [
            'manager' => [ 'index','show','create','store','edit','update','destroy','getModules'],
            'readOnly' => ['index', 'show'],
            'importExport' => ['import', 'export'],
        ];

        // Ajouter le contrôleur
        $sysController = SysController::firstOrCreate(
            ['name' => $controllerName],
            [
                'slug' => Str::slug($controllerName),
                'description' => "Controller for $domainName",
                'module_id' => $sysModule->id,
            ]
        );

        // Ajouter le domaine
        $featureDomain = FeatureDomain::firstOrCreate(
            ['slug' => Str::slug($domainName)],
            [
                'name' => $domainName,
                'description' => "Gestion des $domainName",
                'module_id' => $sysModule->id, // ID dynamique du module
                
            ]
        );

        // Ajouter les fonctionnalités principales
        foreach ($featurePermissions as $featureName => $actions) {
            $feature = Feature::firstOrCreate(
                ['name' => "$domainName - $featureName"],
                [
                    'description' => "Feature $featureName for $domainName",
                    'domain_id' => $featureDomain->id,
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
            'index-module',
            'show-module',
        ])->pluck('name')->toArray();

        // Associer les permissions aux rôles
        $admin->givePermissionTo($adminPermissions);
        $membre->givePermissionTo($memberPermissions);
    }
}
