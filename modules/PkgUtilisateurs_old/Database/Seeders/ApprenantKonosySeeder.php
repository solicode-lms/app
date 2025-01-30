<?php
// TODO : utilisation de create de Service



namespace Modules\PkgUtilisateurs\Database\Seeders;

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
use Modules\PkgUtilisateurs\Models\ApprenantKonosy;
use Modules\PkgUtilisateurs\Services\ApprenantKonosyService;

class ApprenantKonosySeeder extends Seeder
{
    public static int $order = 14;

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

    public function seedFromCsv(): void
    {
        $csvFile = fopen(base_path("modules/PkgUtilisateurs/Database/data/apprenantKonosies.csv"), "r");
        $firstline = true;

        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                (new ApprenantKonosyService())->create([
                    "MatriculeEtudiant" => $data[0] ,
                    "Nom" => $data[1] ,
                    "Prenom" => $data[2] ,
                    "Sexe" => $data[3] ,
                    "EtudiantActif" => $data[4] ,
                    "Diplome" => $data[5] ,
                    "Principale" => $data[6] ,
                    "LibelleLong" => $data[7] ,
                    "CodeDiplome" => $data[8] ,
                    "DateNaissance" => $data[9] ,
                    "DateInscription" => $data[10] ,
                    "LieuNaissance" => $data[11] ,
                    "CIN" => $data[12] ,
                    "NTelephone" => $data[13] ,
                    "Adresse" => $data[14] ,
                    "Nationalite" => $data[15] ,
                    "Nom_Arabe" => $data[16] ,
                    "Prenom_Arabe" => $data[17] ,
                    "NiveauScolaire" => $data[18] 
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }

    private function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgUtilisateurs'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'ApprenantKonosyController';
        $controllerBaseName = 'apprenantKonosy';
        $domainName = 'ApprenantKonosy';

        // Permissions spécifiques pour chaque type de fonctionnalité
        $featurePermissions = [
            'Édition ' => [ 'create','store','edit','update','destroy','getApprenantKonosies'],
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
            'index-apprenantKonosy',
            'show-apprenantKonosy',
        ])->pluck('name')->toArray();

        // Associer les permissions aux rôles
        $admin->givePermissionTo($adminPermissions);
        $membre->givePermissionTo($memberPermissions);
    }
}
