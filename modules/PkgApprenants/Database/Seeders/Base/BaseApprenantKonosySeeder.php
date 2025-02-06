<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Database\Seeders\Base;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Database\Seeders\SysModuleSeeder;
use Modules\Core\Models\Feature;
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Models\SysController;
use Modules\Core\Models\SysModule;
use Modules\PkgApprenants\Models\ApprenantKonosy;
use Modules\PkgApprenants\Services\ApprenantKonosyService;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;


class BaseApprenantKonosySeeder extends Seeder
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
        $filePath = base_path("modules/PkgApprenants/Database/data/apprenantKonosies.csv");
        
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

        $apprenantKonosyService = new ApprenantKonosyService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            
            if ($row) {
                $apprenantKonosyService->create([
                    "MatriculeEtudiant" => $row["MatriculeEtudiant"] ?? null ,
                    "Nom" => $row["Nom"] ?? null ,
                    "Prenom" => $row["Prenom"] ?? null ,
                    "Sexe" => $row["Sexe"] ?? null ,
                    "EtudiantActif" => $row["EtudiantActif"] ?? null ,
                    "Diplome" => $row["Diplome"] ?? null ,
                    "Principale" => $row["Principale"] ?? null ,
                    "LibelleLong" => $row["LibelleLong"] ?? null ,
                    "CodeDiplome" => $row["CodeDiplome"] ?? null ,
                    "DateNaissance" => $row["DateNaissance"] ?? null ,
                    "DateInscription" => $row["DateInscription"] ?? null ,
                    "LieuNaissance" => $row["LieuNaissance"] ?? null ,
                    "CIN" => $row["CIN"] ?? null ,
                    "NTelephone" => $row["NTelephone"] ?? null ,
                    "Adresse" => $row["Adresse"] ?? null ,
                    "Nationalite" => $row["Nationalite"] ?? null ,
                    "Nom_Arabe" => $row["Nom_Arabe"] ?? null ,
                    "Prenom_Arabe" => $row["Prenom_Arabe"] ?? null ,
                    "NiveauScolaire" => $row["NiveauScolaire"] ?? null 
                ]);
            }
        }

        fclose($csvFile);
    }


    private function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgApprenants'; // Slug du module
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
            'Édition ' => [ 'create','store','edit','update','destroy','getApprenantKonosies','dataCalcul'],
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
