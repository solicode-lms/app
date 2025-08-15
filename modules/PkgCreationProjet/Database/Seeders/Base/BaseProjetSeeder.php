<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Database\Seeders\Base;

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
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgCreationProjet\Services\ProjetService;


class BaseProjetSeeder extends Seeder
{
    public static int $order = 33;

    // Permissions spécifiques pour chaque type de fonctionnalité
    protected array  $featurePermissions = [
            'Afficher' => ['show','getData'],
            'Lecture' => ['index', 'show','getData'],
            'Édition sans Ajouter' => ['index', 'show','edit','update','dataCalcul','getData'],
            'Édition ' => [ 'index', 'show','create','store','edit','update','destroy','dataCalcul','getData'],
            'Extraction' => ['import', 'export'],
            'clonerProjet' => ['clonerProjet'],
            
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
        $filePath = base_path("modules/PkgCreationProjet/Database/data/projets.csv");
        
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

        $projetService = new ProjetService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $session_formation_id = null;
                if (!empty($row["session_formation_reference"])) {
                    $session_formation_id = \Modules\PkgSessions\Models\SessionFormation::where('reference', $row["session_formation_reference"])
                        ->value('id');
                }
                $filiere_id = null;
                if (!empty($row["filiere_reference"])) {
                    $filiere_id = \Modules\PkgFormation\Models\Filiere::where('reference', $row["filiere_reference"])
                        ->value('id');
                }
                $formateur_id = null;
                if (!empty($row["formateur_reference"])) {
                    $formateur_id = \Modules\PkgFormation\Models\Formateur::where('reference', $row["formateur_reference"])
                        ->value('id');
                }


                $projetData =[
                        "session_formation_id" => $session_formation_id,
                        "filiere_id" => $filiere_id,
                        "titre" => isset($row["titre"]) && $row["titre"] !== "" ? $row["titre"] : null,
                        "travail_a_faire" => isset($row["travail_a_faire"]) && $row["travail_a_faire"] !== "" ? $row["travail_a_faire"] : null,
                        "critere_de_travail" => isset($row["critere_de_travail"]) && $row["critere_de_travail"] !== "" ? $row["critere_de_travail"] : null,
                        "formateur_id" => $formateur_id,
                        "description" => isset($row["description"]) && $row["description"] !== "" ? $row["description"] : null,
                    "reference" => $row["reference"] ?? null ,
                ];

                $projet = null;
                if (!empty($row["reference"])) {
                    $projet = $projetService->updateOrCreate(["reference" => $row["reference"]], $projetData);
                } else {
                    $projet = $projetService->create($projetData);
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgCreationProjet'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'ProjetController';
        $controllerBaseName = 'projet';
        $domainName = 'Projet';

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
