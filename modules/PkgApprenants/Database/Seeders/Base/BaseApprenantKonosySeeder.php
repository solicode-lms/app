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
    public static int $order = 15;

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




                $apprenantKonosyData =[
                        "MatriculeEtudiant" => !empty($row["MatriculeEtudiant"]) ? $row["MatriculeEtudiant"] : null,
                        "Nom" => !empty($row["Nom"]) ? $row["Nom"] : null,
                        "Prenom" => !empty($row["Prenom"]) ? $row["Prenom"] : null,
                        "Sexe" => !empty($row["Sexe"]) ? $row["Sexe"] : null,
                        "EtudiantActif" => !empty($row["EtudiantActif"]) ? $row["EtudiantActif"] : null,
                        "Diplome" => !empty($row["Diplome"]) ? $row["Diplome"] : null,
                        "Principale" => !empty($row["Principale"]) ? $row["Principale"] : null,
                        "LibelleLong" => !empty($row["LibelleLong"]) ? $row["LibelleLong"] : null,
                        "CodeDiplome" => !empty($row["CodeDiplome"]) ? $row["CodeDiplome"] : null,
                        "DateNaissance" => !empty($row["DateNaissance"]) ? $row["DateNaissance"] : null,
                        "DateInscription" => !empty($row["DateInscription"]) ? $row["DateInscription"] : null,
                        "LieuNaissance" => !empty($row["LieuNaissance"]) ? $row["LieuNaissance"] : null,
                        "CIN" => !empty($row["CIN"]) ? $row["CIN"] : null,
                        "NTelephone" => !empty($row["NTelephone"]) ? $row["NTelephone"] : null,
                        "Adresse" => !empty($row["Adresse"]) ? $row["Adresse"] : null,
                        "Nationalite" => !empty($row["Nationalite"]) ? $row["Nationalite"] : null,
                        "Nom_Arabe" => !empty($row["Nom_Arabe"]) ? $row["Nom_Arabe"] : null,
                        "Prenom_Arabe" => !empty($row["Prenom_Arabe"]) ? $row["Prenom_Arabe"] : null,
                        "NiveauScolaire" => !empty($row["NiveauScolaire"]) ? $row["NiveauScolaire"] : null,
                    "reference" => $row["reference"] ?? null ,
                ];
                if (!empty($row["reference"])) {
                    $apprenantKonosyService->updateOrCreate(["reference" => $row["reference"]], $apprenantKonosyData);
                } else {
                    $apprenantKonosyService->create($apprenantKonosyData);
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
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
