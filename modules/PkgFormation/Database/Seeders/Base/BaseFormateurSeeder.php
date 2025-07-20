<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Database\Seeders\Base;

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
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgFormation\Services\FormateurService;


class BaseFormateurSeeder extends Seeder
{
    public static int $order = 25;

    // Permissions spécifiques pour chaque type de fonctionnalité
    protected array  $featurePermissions = [
            'Afficher' => ['show','getData'],
            'Lecture' => ['index', 'show','getData'],
            'Édition sans Ajouter' => ['index', 'show','edit','update','dataCalcul','getData'],
            'Édition ' => [ 'index', 'show','create','store','edit','update','destroy','dataCalcul','getData'],
            'Extraction' => ['import', 'export'],
            'initPassword' => ['initPassword'],
            
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
        $filePath = base_path("modules/PkgFormation/Database/data/formateurs.csv");
        
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

        $formateurService = new FormateurService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $user_id = null;
                if (!empty($row["user_reference"])) {
                    $user_id = \Modules\PkgAutorisation\Models\User::where('reference', $row["user_reference"])
                        ->value('id');
                }


                $formateurData =[
                        "matricule" => isset($row["matricule"]) && $row["matricule"] !== "" ? $row["matricule"] : null,
                        "nom" => isset($row["nom"]) && $row["nom"] !== "" ? $row["nom"] : null,
                        "prenom" => isset($row["prenom"]) && $row["prenom"] !== "" ? $row["prenom"] : null,
                        "prenom_arab" => isset($row["prenom_arab"]) && $row["prenom_arab"] !== "" ? $row["prenom_arab"] : null,
                        "nom_arab" => isset($row["nom_arab"]) && $row["nom_arab"] !== "" ? $row["nom_arab"] : null,
                        "email" => isset($row["email"]) && $row["email"] !== "" ? $row["email"] : null,
                        "tele_num" => isset($row["tele_num"]) && $row["tele_num"] !== "" ? $row["tele_num"] : null,
                        "adresse" => isset($row["adresse"]) && $row["adresse"] !== "" ? $row["adresse"] : null,
                        "diplome" => isset($row["diplome"]) && $row["diplome"] !== "" ? $row["diplome"] : null,
                        "echelle" => isset($row["echelle"]) && $row["echelle"] !== "" ? $row["echelle"] : null,
                        "echelon" => isset($row["echelon"]) && $row["echelon"] !== "" ? $row["echelon"] : null,
                        "profile_image" => isset($row["profile_image"]) && $row["profile_image"] !== "" ? $row["profile_image"] : null,
                        "user_id" => $user_id,
                    "reference" => $row["reference"] ?? null ,
                ];
                if (!empty($row["reference"])) {
                    $formateurService->updateOrCreate(["reference" => $row["reference"]], $formateurData);
                } else {
                    $formateurService->create($formateurData);
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgFormation'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'FormateurController';
        $controllerBaseName = 'formateur';
        $domainName = 'Formateur';

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
