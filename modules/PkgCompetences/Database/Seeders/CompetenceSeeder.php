<?php

namespace Modules\PkgCompetences\Database\Seeders;

use Modules\PkgCompetences\Models\Competence;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;
use Illuminate\Support\Str;
use Modules\Core\Models\Feature;
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Models\SysController;
use Modules\Core\Models\SysModule;

class CompetenceSeeder extends Seeder
{
    public static int $order = 18;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // Supprimer les anciennes données pour éviter les doublons
        Schema::disableForeignKeyConstraints();
        Competence::truncate();
        Schema::enableForeignKeyConstraints();

        // Ajouter les compétences à partir d'un fichier CSV
        $this->seedCompetencesFromCsv();

        // Ajouter le contrôleur, le domaine, les fonctionnalités et leurs permissions
        $this->addDefaultControllerDomainFeatures();

        // Associer les permissions aux rôles
        $this->assignPermissionsToRoles($AdminRole, $MembreRole);
    }

    private function seedCompetencesFromCsv(): void
    {
        $csvFile = fopen(base_path("modules/PkgCompetences/Database/data/competences.csv"), "r");
        $firstline = true;

        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Competence::create([
                    "code" => $data[0],
                    "nom" => $data[1],
                    "description" => $data[2],
                    "module_id" => $data[3],
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
        $controllerName = 'CompetenceController';
        $domainName = 'Gestion des compétences';

        // Permissions spécifiques pour chaque type de fonctionnalité
        $featurePermissions = [
            'manager' => [ 'index','show','create','store','edit','update','destroy','getCompetences'],
            'readOnly' => ['index', 'show'],
            'importExport' => ['import', 'export'],
        ];

        // Ajouter le contrôleur
        $sysController = SysController::firstOrCreate(
            ['name' => $controllerName],
            [
                'slug' => Str::slug($controllerName),
                'description' => "Controller for $domainName",
            ]
        );

        // Ajouter le domaine
        $featureDomain = FeatureDomain::firstOrCreate(
            ['slug' => Str::slug($domainName)],
            [
                'name' => $domainName,
                'description' => "Domain for $domainName",
                'module_id' => $sysModule->id, // ID dynamique du module
            ]
        );

        // Ajouter les fonctionnalités principales
        foreach ($featurePermissions as $featureName => $actions) {
            $feature = Feature::firstOrCreate(
                ['name' => "$domainName - $featureName"],
                [
                    'slug' => Str::slug("$domainName-$featureName"),
                    'description' => "Feature $featureName for $domainName",
                    'domain_id' => $featureDomain->id,
                    'module_id' => $sysModule->id,
                ]
            );

            // Ajouter les Permissions liées uniquement à la Feature
            $permissionIds = [];
            foreach ($actions as $action) {
                $permission = Permission::firstOrCreate(
                    ['name' => "$action-$controllerName"],
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
            'index-CompetenceController',
            'show-CompetenceController',
        ])->pluck('name')->toArray();

        // Associer les permissions aux rôles
        $admin->givePermissionTo($adminPermissions);
        $membre->givePermissionTo($memberPermissions);
    }
}
