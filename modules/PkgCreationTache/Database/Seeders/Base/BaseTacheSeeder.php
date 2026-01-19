<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationTache\Database\Seeders\Base;

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
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgCreationTache\Services\TacheService;


class BaseTacheSeeder extends Seeder
{
    public static int $order = 50;

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
        $filePath = base_path("modules/PkgCreationTache/Database/data/taches.csv");
        
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

        $tacheService = new TacheService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $projet_id = null;
                if (!empty($row["projet_reference"])) {
                    $projet_id = \Modules\PkgCreationProjet\Models\Projet::where('reference', $row["projet_reference"])
                        ->value('id');
                }
                $phase_projet_id = null;
                if (!empty($row["phase_projet_reference"])) {
                    $phase_projet_id = \Modules\PkgCreationTache\Models\PhaseProjet::where('reference', $row["phase_projet_reference"])
                        ->value('id');
                }
                $phase_evaluation_id = null;
                if (!empty($row["phase_evaluation_reference"])) {
                    $phase_evaluation_id = \Modules\PkgCompetences\Models\PhaseEvaluation::where('reference', $row["phase_evaluation_reference"])
                        ->value('id');
                }
                $chapitre_id = null;
                if (!empty($row["chapitre_reference"])) {
                    $chapitre_id = \Modules\PkgCompetences\Models\Chapitre::where('reference', $row["chapitre_reference"])
                        ->value('id');
                }
                $mobilisation_ua_id = null;
                if (!empty($row["mobilisation_ua_reference"])) {
                    $mobilisation_ua_id = \Modules\PkgCreationProjet\Models\MobilisationUa::where('reference', $row["mobilisation_ua_reference"])
                        ->value('id');
                }


                $tacheData =[
                        "ordre" => isset($row["ordre"]) && $row["ordre"] !== "" ? $row["ordre"] : null,
                        "priorite" => isset($row["priorite"]) && $row["priorite"] !== "" ? $row["priorite"] : null,
                        "titre" => isset($row["titre"]) && $row["titre"] !== "" ? $row["titre"] : null,
                        "projet_id" => $projet_id,
                        "description" => isset($row["description"]) && $row["description"] !== "" ? $row["description"] : null,
                        "dateDebut" => isset($row["dateDebut"]) && $row["dateDebut"] !== "" ? $row["dateDebut"] : null,
                        "dateFin" => isset($row["dateFin"]) && $row["dateFin"] !== "" ? $row["dateFin"] : null,
                        "note" => isset($row["note"]) && $row["note"] !== "" ? $row["note"] : null,
                        "phase_projet_id" => $phase_projet_id,
                        "is_live_coding_task" => isset($row["is_live_coding_task"]) && $row["is_live_coding_task"] !== "" ? $row["is_live_coding_task"] : null,
                        "phase_evaluation_id" => $phase_evaluation_id,
                        "chapitre_id" => $chapitre_id,
                        "mobilisation_ua_id" => $mobilisation_ua_id,
                    "reference" => $row["reference"] ?? null ,
                ];

                $tache = null;
                if (!empty($row["reference"])) {
                    $tache = $tacheService->updateOrCreate(["reference" => $row["reference"]], $tacheData);
                } else {
                    $tache = $tacheService->create($tacheData);
                }
                if (!empty($row["livrables"])) {
                    $livrableReferences = array_map('trim', explode('|', $row["livrables"]));
                    $livrableIds = \Modules\PkgAutorisation\Models\Role::whereIn('reference', $livrableReferences)->pluck('id')->toArray();

                    if (!empty($livrableIds)) {
                        $tache->livrables()->sync($livrableIds);
                          $tache->touch(); // pour lancer Observer
                    }
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgCreationTache'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'TacheController';
        $controllerBaseName = 'tache';
        $domainName = 'Tache';

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
