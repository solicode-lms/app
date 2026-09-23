<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\Database\Seeders\Base;

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
use Modules\PkgQcm\Models\PropositionReponse;
use Modules\PkgQcm\Services\PropositionReponseService;


class BasePropositionReponseSeeder extends Seeder
{
    public static int $order = 105;

    // Permissions spécifiques pour chaque type de fonctionnalité
    protected array  $featurePermissions = [
            'Afficher' => ['show','getData'],
            'Lecture' => ['index', 'show','getData'],
            'Édition sans Ajouter' => ['index', 'show','edit','update','dataCalcul','getData'],
            'Édition ' => [ 'index', 'show','create','store','edit','update','destroy','dataCalcul','getData'],
            'Extraction' => ['import', 'export'],
            'Import' => ['import'],
            'Export' => ['export'],

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
        $filePath = base_path("modules/PkgQcm/Database/data/propositionReponses.csv");
        
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

        $propositionReponseService = new PropositionReponseService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $question_lib_id = null;
                if (!empty($row["question_lib_reference"])) {
                    $question_lib_id = \Modules\PkgQcm\Models\Question::where('reference', $row["question_reference"])
                        ->value('id');
                }


                $propositionReponseData =[
                        "ordre" => isset($row["ordre"]) && $row["ordre"] !== "" ? $row["ordre"] : null,
                        "libelle" => isset($row["libelle"]) && $row["libelle"] !== "" ? $row["libelle"] : null,
                        "is_correcte" => isset($row["is_correcte"]) && $row["is_correcte"] !== "" ? $row["is_correcte"] : null,
                        "question_lib_id" => $question_lib_id,
                    "reference" => $row["reference"] ?? null ,
                ];

                $propositionReponse = null;
                if (!empty($row["reference"])) {
                    $propositionReponse = $propositionReponseService->updateOrCreate(["reference" => $row["reference"]], $propositionReponseData);
                } else {
                    $propositionReponse = $propositionReponseService->create($propositionReponseData);
                }
                if (!empty($row["reponseQcms"])) {
                    $reponseQcmReferences = array_map('trim', explode('|', $row["reponseQcms"]));
                    $reponseQcmIds = \Modules\PkgAutorisation\Models\Role::whereIn('reference', $reponseQcmReferences)->pluck('id')->toArray();

                    if (!empty($reponseQcmIds)) {
                        $propositionReponse->reponseQcms()->sync($reponseQcmIds);
                          $propositionReponse->touch(); // pour lancer Observer
                    }
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgQcm'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'PropositionReponseController';
        $controllerBaseName = 'propositionReponse';
        $domainName = 'PropositionReponse';

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
