<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Database\Seeders\Base;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Database\Seeders\SysModuleSeeder;
use Modules\Core\Models\Feature;
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Models\SysController;
use Modules\Core\Models\SysModule;
use Modules\Core\Services\FeatureDomainService;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;


class BaseFeatureDomainSeeder extends Seeder
{
    public static int $order = 9;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // Ajouter les données à partir d'un fichier CSV
        $this->seedFromCsv();

        // Ajouter le contrôleur, le domaine, les fonctionnalités et leurs permissions
        $this->addDefaultControllerDomainFeatures();

    }

    public function seedFromCsv(): void
    {
        $filePath = base_path("modules/Core/Database/data/featureDomains.csv");
        
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

        $featureDomainService = new FeatureDomainService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            
            if ($row) {
                $featureDomainService->create([
                    "name" => $row["name"] ?? null ,
                    "slug" => $row["slug"] ?? null ,
                    "description" => $row["description"] ?? null ,
                    "sys_module_id" => $row["sys_module_id"] ?? null 
                ]);
            }
        }

        fclose($csvFile);
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
        $controllerName = 'FeatureDomainController';
        $controllerBaseName = 'featureDomain';
        $domainName = 'FeatureDomain';

        // Permissions spécifiques pour chaque type de fonctionnalité
        $featurePermissions = [
            'Afficher' => ['show'],
            'Lecture' => ['index', 'show'],
            'Édition sans Ajouter' => ['index', 'show','edit','update','destroy','getFeatureDomains','dataCalcul'],
            'Édition ' => [ 'index', 'show','create','store','edit','update','destroy','getFeatureDomains','dataCalcul'],
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
}
