<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgEvaluateurs\Database\Seeders\Base;

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
use Modules\PkgEvaluateurs\Models\EvaluationRealisationTache;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService;


class BaseEvaluationRealisationTacheSeeder extends Seeder
{
    public static int $order = 64;

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
        $filePath = base_path("modules/PkgEvaluateurs/Database/data/evaluationRealisationTaches.csv");
        
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

        $evaluationRealisationTacheService = new EvaluationRealisationTacheService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $realisation_tache_id = null;
                if (!empty($row["realisation_tache_reference"])) {
                    $realisation_tache_id = \Modules\PkgRealisationTache\Models\RealisationTache::where('reference', $row["realisation_tache_reference"])
                        ->value('id');
                }
                $evaluateur_id = null;
                if (!empty($row["evaluateur_reference"])) {
                    $evaluateur_id = \Modules\PkgEvaluateurs\Models\Evaluateur::where('reference', $row["evaluateur_reference"])
                        ->value('id');
                }
                $evaluation_realisation_projet_id = null;
                if (!empty($row["evaluation_realisation_projet_reference"])) {
                    $evaluation_realisation_projet_id = \Modules\PkgEvaluateurs\Models\EvaluationRealisationProjet::where('reference', $row["evaluation_realisation_projet_reference"])
                        ->value('id');
                }


                $evaluationRealisationTacheData =[
                        "realisation_tache_id" => $realisation_tache_id,
                        "evaluateur_id" => $evaluateur_id,
                        "note" => isset($row["note"]) && $row["note"] !== "" ? $row["note"] : null,
                        "message" => isset($row["message"]) && $row["message"] !== "" ? $row["message"] : null,
                        "evaluation_realisation_projet_id" => $evaluation_realisation_projet_id,
                    "reference" => $row["reference"] ?? null ,
                ];
                if (!empty($row["reference"])) {
                    $evaluationRealisationTacheService->updateOrCreate(["reference" => $row["reference"]], $evaluationRealisationTacheData);
                } else {
                    $evaluationRealisationTacheService->create($evaluationRealisationTacheData);
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgEvaluateurs'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'EvaluationRealisationTacheController';
        $controllerBaseName = 'evaluationRealisationTache';
        $domainName = 'EvaluationRealisationTache';

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
