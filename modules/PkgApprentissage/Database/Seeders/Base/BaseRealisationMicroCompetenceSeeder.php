<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Database\Seeders\Base;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Database\Seeders\SysModuleSeeder;
use Modules\Core\Models\Feature;
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Models\SysController;
use Modules\Core\Models\SysModule;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
use Modules\PkgApprentissage\Services\RealisationMicroCompetenceService;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;


class BaseRealisationMicroCompetenceSeeder extends Seeder
{
    public static int $order = 82;

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
        $filePath = base_path("modules/PkgApprentissage/Database/data/realisationMicroCompetences.csv");
        
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

        $realisationMicroCompetenceService = new RealisationMicroCompetenceService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $micro_competence_id = null;
                if (!empty($row["micro_competence_reference"])) {
                    $micro_competence_id = \Modules\PkgCompetences\Models\MicroCompetence::where('reference', $row["micro_competence_reference"])
                        ->value('id');
                }
                $apprenant_id = null;
                if (!empty($row["apprenant_reference"])) {
                    $apprenant_id = \Modules\PkgApprenants\Models\Apprenant::where('reference', $row["apprenant_reference"])
                        ->value('id');
                }
                $etat_realisation_micro_competence_id = null;
                if (!empty($row["etat_realisation_micro_competence_reference"])) {
                    $etat_realisation_micro_competence_id = \Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence::where('reference', $row["etat_realisation_micro_competence_reference"])
                        ->value('id');
                }
                $realisation_competence_id = null;
                if (!empty($row["realisation_competence_reference"])) {
                    $realisation_competence_id = \Modules\PkgApprentissage\Models\RealisationCompetence::where('reference', $row["realisation_competence_reference"])
                        ->value('id');
                }


                $realisationMicroCompetenceData =[
                        "micro_competence_id" => $micro_competence_id,
                        "apprenant_id" => $apprenant_id,
                        "note_cache" => isset($row["note_cache"]) && $row["note_cache"] !== "" ? $row["note_cache"] : null,
                        "progression_cache" => isset($row["progression_cache"]) && $row["progression_cache"] !== "" ? $row["progression_cache"] : null,
                        "etat_realisation_micro_competence_id" => $etat_realisation_micro_competence_id,
                        "bareme_cache" => isset($row["bareme_cache"]) && $row["bareme_cache"] !== "" ? $row["bareme_cache"] : null,
                        "commentaire_formateur" => isset($row["commentaire_formateur"]) && $row["commentaire_formateur"] !== "" ? $row["commentaire_formateur"] : null,
                        "date_debut" => isset($row["date_debut"]) && $row["date_debut"] !== "" ? $row["date_debut"] : null,
                        "date_fin" => isset($row["date_fin"]) && $row["date_fin"] !== "" ? $row["date_fin"] : null,
                        "dernier_update" => isset($row["dernier_update"]) && $row["dernier_update"] !== "" ? $row["dernier_update"] : null,
                        "realisation_competence_id" => $realisation_competence_id,
                    "reference" => $row["reference"] ?? null ,
                ];
                if (!empty($row["reference"])) {
                    $realisationMicroCompetenceService->updateOrCreate(["reference" => $row["reference"]], $realisationMicroCompetenceData);
                } else {
                    $realisationMicroCompetenceService->create($realisationMicroCompetenceData);
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgApprentissage'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'RealisationMicroCompetenceController';
        $controllerBaseName = 'realisationMicroCompetence';
        $domainName = 'RealisationMicroCompetence';

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
