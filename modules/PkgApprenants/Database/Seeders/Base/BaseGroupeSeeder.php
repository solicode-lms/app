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
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;


class BaseGroupeSeeder extends Seeder
{
    public static int $order = 22;

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
        $filePath = base_path("modules/PkgApprenants/Database/data/groupes.csv");
        
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

        $groupeService = new GroupeService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $filiere_id = null;
                if (!empty($row["filiere_reference"])) {
                    $filiere_id = \Modules\PkgFormation\Models\Filiere::where('reference', $row["filiere_reference"])
                        ->value('id');
                }
                $annee_formation_id = null;
                if (!empty($row["annee_formation_reference"])) {
                    $annee_formation_id = \Modules\PkgFormation\Models\AnneeFormation::where('reference', $row["annee_formation_reference"])
                        ->value('id');
                }


                $groupeData =[
                        "code" => isset($row["code"]) && $row["code"] !== "" ? $row["code"] : null,
                        "nom" => isset($row["nom"]) && $row["nom"] !== "" ? $row["nom"] : null,
                        "description" => isset($row["description"]) && $row["description"] !== "" ? $row["description"] : null,
                        "filiere_id" => $filiere_id,
                        "annee_formation_id" => $annee_formation_id,
                    "reference" => $row["reference"] ?? null ,
                ];

                $groupe = null;
                if (!empty($row["reference"])) {
                    $groupe = $groupeService->updateOrCreate(["reference" => $row["reference"]], $groupeData);
                } else {
                    $groupe = $groupeService->create($groupeData);
                }
                if (!empty($row["apprenants"])) {
                    $apprenantReferences = array_map('trim', explode('|', $row["apprenants"]));
                    $apprenantIds = \Modules\PkgAutorisation\Models\Role::whereIn('reference', $apprenantReferences)->pluck('id')->toArray();

                    if (!empty($apprenantIds)) {
                        $groupe->apprenants()->sync($apprenantIds);
                          $groupe->touch(); // pour lancer Observer
                    }
                }
                if (!empty($row["formateurs"])) {
                    $formateurReferences = array_map('trim', explode('|', $row["formateurs"]));
                    $formateurIds = \Modules\PkgAutorisation\Models\Role::whereIn('reference', $formateurReferences)->pluck('id')->toArray();

                    if (!empty($formateurIds)) {
                        $groupe->formateurs()->sync($formateurIds);
                          $groupe->touch(); // pour lancer Observer
                    }
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
        $controllerName = 'GroupeController';
        $controllerBaseName = 'groupe';
        $domainName = 'Groupe';

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
