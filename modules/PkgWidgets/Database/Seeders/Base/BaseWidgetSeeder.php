<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Database\Seeders\Base;

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
use Modules\PkgWidgets\Models\Widget;
use Modules\PkgWidgets\Services\WidgetService;


class BaseWidgetSeeder extends Seeder
{
    public static int $order = 31;

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
        $filePath = base_path("modules/PkgWidgets/Database/data/widgets.csv");
        
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

        $widgetService = new WidgetService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $type_id = null;
                if (!empty($row["widget_type_reference"])) {
                    $type_id = \Modules\PkgWidgets\Models\WidgetType::where('reference', $row["widget_type_reference"])
                        ->value('id');
                }
                $model_id = null;
                if (!empty($row["sys_model_reference"])) {
                    $model_id = \Modules\Core\Models\SysModel::where('reference', $row["sys_model_reference"])
                        ->value('id');
                }
                $operation_id = null;
                if (!empty($row["widget_operation_reference"])) {
                    $operation_id = \Modules\PkgWidgets\Models\WidgetOperation::where('reference', $row["widget_operation_reference"])
                        ->value('id');
                }
                $sys_color_id = null;
                if (!empty($row["sys_color_reference"])) {
                    $sys_color_id = \Modules\Core\Models\SysColor::where('reference', $row["sys_color_reference"])
                        ->value('id');
                }
                $section_widget_id = null;
                if (!empty($row["section_widget_reference"])) {
                    $section_widget_id = \Modules\PkgWidgets\Models\SectionWidget::where('reference', $row["section_widget_reference"])
                        ->value('id');
                }


                $widgetData =[
                        "ordre" => isset($row["ordre"]) && $row["ordre"] !== "" ? $row["ordre"] : null,
                        "icon" => isset($row["icon"]) && $row["icon"] !== "" ? $row["icon"] : null,
                        "name" => isset($row["name"]) && $row["name"] !== "" ? $row["name"] : null,
                        "label" => isset($row["label"]) && $row["label"] !== "" ? $row["label"] : null,
                        "type_id" => $type_id,
                        "model_id" => $model_id,
                        "operation_id" => $operation_id,
                        "color" => isset($row["color"]) && $row["color"] !== "" ? $row["color"] : null,
                        "sys_color_id" => $sys_color_id,
                        "section_widget_id" => $section_widget_id,
                        "parameters" => isset($row["parameters"]) && $row["parameters"] !== "" ? $row["parameters"] : null,
                    "reference" => $row["reference"] ?? null ,
                ];
                if (!empty($row["reference"])) {
                    $widgetService->updateOrCreate(["reference" => $row["reference"]], $widgetData);
                } else {
                    $widgetService->create($widgetData);
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgWidgets'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'WidgetController';
        $controllerBaseName = 'widget';
        $domainName = 'Widget';

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
