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
use Modules\PkgGapp\Models\EMetadatum;
use Modules\PkgGapp\Services\EMetadatumService;


class BaseEMetadatumSeeder extends Seeder
{
    public static int $order = 41;

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
        $filePath = base_path("modules/PkgGapp/Database/data/eMetadata.csv");
        
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

        $eMetadatumService = new EMetadatumService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $e_model_id = null;
                if (!empty($row["e_model_reference"])) {
                    $e_model_id = \Modules\PkgGapp\Models\EModel::where('reference', $row["e_model_reference"])
                        ->value('id');
                }
                $e_data_field_id = null;
                if (!empty($row["e_data_field_reference"])) {
                    $e_data_field_id = \Modules\PkgGapp\Models\EDataField::where('reference', $row["e_data_field_reference"])
                        ->value('id');
                }
                $e_metadata_definition_id = null;
                if (!empty($row["e_metadata_definition_reference"])) {
                    $e_metadata_definition_id = \Modules\PkgGapp\Models\EMetadataDefinition::where('reference', $row["e_metadata_definition_reference"])
                        ->value('id');
                }


                $eMetadatumData =[
                        "value_boolean" => !empty($row["value_boolean"]) ? $row["value_boolean"] : null,
                        "value_string" => !empty($row["value_string"]) ? $row["value_string"] : null,
                        "value_integer" => !empty($row["value_integer"]) ? $row["value_integer"] : null,
                        "value_float" => !empty($row["value_float"]) ? $row["value_float"] : null,
                        "value_date" => !empty($row["value_date"]) ? $row["value_date"] : null,
                        "value_datetime" => !empty($row["value_datetime"]) ? $row["value_datetime"] : null,
                        "value_enum" => !empty($row["value_enum"]) ? $row["value_enum"] : null,
                        "value_json" => !empty($row["value_json"]) ? $row["value_json"] : null,
                        "value_text" => !empty($row["value_text"]) ? $row["value_text"] : null,
                        "e_model_id" => $e_model_id,
                        "e_data_field_id" => $e_data_field_id,
                        "e_metadata_definition_id" => $e_metadata_definition_id,
                    "reference" => $row["reference"] ?? null ,
                ];
                if (!empty($row["reference"])) {
                    $eMetadatumService->updateOrCreate(["reference" => $row["reference"]], $eMetadatumData);
                } else {
                    $eMetadatumService->create($eMetadatumData);
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
        $controllerName = 'EMetadatumController';
        $controllerBaseName = 'eMetadatum';
        $domainName = 'EMetadatum';

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
