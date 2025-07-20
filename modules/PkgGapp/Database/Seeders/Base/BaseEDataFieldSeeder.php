<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Database\Seeders\Base;

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
use Modules\PkgGapp\Models\EDataField;
use Modules\PkgGapp\Services\EDataFieldService;


class BaseEDataFieldSeeder extends Seeder
{
    public static int $order = 40;

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
        $filePath = base_path("modules/PkgGapp/Database/data/eDataFields.csv");
        
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

        $eDataFieldService = new EDataFieldService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $e_model_id = null;
                if (!empty($row["e_model_reference"])) {
                    $e_model_id = \Modules\PkgGapp\Models\EModel::where('reference', $row["e_model_reference"])
                        ->value('id');
                }
                $e_relationship_id = null;
                if (!empty($row["e_relationship_reference"])) {
                    $e_relationship_id = \Modules\PkgGapp\Models\ERelationship::where('reference', $row["e_relationship_reference"])
                        ->value('id');
                }


                $eDataFieldData =[
                        "name" => $row["name"] === "" ? null : $row["name"],
                        "e_model_id" => $e_model_id,
                        "data_type" => $row["data_type"] === "" ? null : $row["data_type"],
                        "default_value" => $row["default_value"] === "" ? null : $row["default_value"],
                        "column_name" => $row["column_name"] === "" ? null : $row["column_name"],
                        "e_relationship_id" => $e_relationship_id,
                        "field_order" => $row["field_order"] === "" ? null : $row["field_order"],
                        "db_primaryKey" => $row["db_primaryKey"] === "" ? null : $row["db_primaryKey"],
                        "db_nullable" => $row["db_nullable"] === "" ? null : $row["db_nullable"],
                        "db_unique" => $row["db_unique"] === "" ? null : $row["db_unique"],
                        "calculable" => $row["calculable"] === "" ? null : $row["calculable"],
                        "calculable_sql" => $row["calculable_sql"] === "" ? null : $row["calculable_sql"],
                        "description" => $row["description"] === "" ? null : $row["description"],
                    "reference" => $row["reference"] ?? null ,
                ];
                if (!empty($row["reference"])) {
                    $eDataFieldService->updateOrCreate(["reference" => $row["reference"]], $eDataFieldData);
                } else {
                    $eDataFieldService->create($eDataFieldData);
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgGapp'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'EDataFieldController';
        $controllerBaseName = 'eDataField';
        $domainName = 'EDataField';

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
