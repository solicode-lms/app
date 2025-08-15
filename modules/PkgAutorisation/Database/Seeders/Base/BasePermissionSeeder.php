<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\Database\Seeders\Base;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Database\Seeders\SysModuleSeeder;
use Modules\Core\Models\Feature;
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Models\SysController;
use Modules\Core\Models\SysModule;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgAutorisation\Services\PermissionService;


class BasePermissionSeeder extends Seeder
{
    public static int $order = 4;

    // Permissions spécifiques pour chaque type de fonctionnalité
    protected array  $featurePermissions = [
            'Afficher' => ['show','getData'],
            'Lecture' => ['index', 'show','getData'],
            'Édition sans Ajouter' => ['index', 'show','edit','update','dataCalcul','getData'],
            'Édition ' => [ 'index', 'show','create','store','edit','update','destroy','dataCalcul','getData'],
            'Extraction' => ['import', 'export'],

        ];

    public function run(): void
    {

        // Ajouter les données à partir d'un fichier CSV
        $this->seedFromCsv();

        // Ajouter le contrôleur, le domaine, les fonctionnalités et leurs permissions
        $this->addDefaultControllerDomainFeatures();

    }

    public function seedFromCsv(): void
    {
        $filePath = base_path("modules/PkgAutorisation/Database/data/permissions.csv");
        
        if (!file_exists($filePath) || filesize($filePath) === 0) {
            return;
        }

        $csvFile = fopen($filePath, "r");
        if (!$csvFile) {
            return; 
        }

        // Lire la première ligne pour récupérer les noms des colonnes
        $headers = fgetcsv($csvFile);
        if (!$headers) {
            fclose($csvFile);
            return;
        }

        $permissionService = new PermissionService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $controller_id = null;
                if (!empty($row["sys_controller_reference"])) {
                    $controller_id = \Modules\Core\Models\SysController::where('reference', $row["sys_controller_reference"])
                        ->value('id');
                }


                $permissionData =[
                        "name" => isset($row["name"]) && $row["name"] !== "" ? $row["name"] : null,
                        "guard_name" => isset($row["guard_name"]) && $row["guard_name"] !== "" ? $row["guard_name"] : null,
                        "controller_id" => $controller_id,
                    "reference" => $row["reference"] ?? null ,
                ];

                $permission = null;
                if (!empty($row["reference"])) {
                    $permission = $permissionService->updateOrCreate(["reference" => $row["reference"]], $permissionData);
                } else {
                    $permission = $permissionService->create($permissionData);
                }
                if (!empty($row["features"])) {
                    $featureReferences = array_map('trim', explode('|', $row["features"]));
                    $featureIds = \Modules\PkgAutorisation\Models\Role::whereIn('reference', $featureReferences)->pluck('id')->toArray();

                    if (!empty($featureIds)) {
                        $permission->features()->sync($featureIds);
                          $permission->touch(); // pour lancer Observer
                    }
                }
                if (!empty($row["roles"])) {
                    $roleReferences = array_map('trim', explode('|', $row["roles"]));
                    $roleIds = \Modules\PkgAutorisation\Models\Role::whereIn('reference', $roleReferences)->pluck('id')->toArray();

                    if (!empty($roleIds)) {
                        $permission->roles()->sync($roleIds);
                          $permission->touch(); // pour lancer Observer
                    }
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgAutorisation'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'PermissionController';
        $controllerBaseName = 'permission';
        $domainName = 'Permission';

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
        foreach ($this->featurePermissions as $featureName => $actions) {
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
}
