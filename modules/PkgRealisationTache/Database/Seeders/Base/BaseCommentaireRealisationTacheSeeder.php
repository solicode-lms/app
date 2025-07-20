<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Database\Seeders\Base;

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
use Modules\PkgRealisationTache\Models\CommentaireRealisationTache;
use Modules\PkgRealisationTache\Services\CommentaireRealisationTacheService;


class BaseCommentaireRealisationTacheSeeder extends Seeder
{
    public static int $order = 53;

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
        $filePath = base_path("modules/PkgRealisationTache/Database/data/commentaireRealisationTaches.csv");
        
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

        $commentaireRealisationTacheService = new CommentaireRealisationTacheService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $realisation_tache_id = null;
                if (!empty($row["realisation_tache_reference"])) {
                    $realisation_tache_id = \Modules\PkgRealisationTache\Models\RealisationTache::where('reference', $row["realisation_tache_reference"])
                        ->value('id');
                }
                $formateur_id = null;
                if (!empty($row["formateur_reference"])) {
                    $formateur_id = \Modules\PkgFormation\Models\Formateur::where('reference', $row["formateur_reference"])
                        ->value('id');
                }
                $apprenant_id = null;
                if (!empty($row["apprenant_reference"])) {
                    $apprenant_id = \Modules\PkgApprenants\Models\Apprenant::where('reference', $row["apprenant_reference"])
                        ->value('id');
                }


                $commentaireRealisationTacheData =[
                        "commentaire" => isset($row["commentaire"]) && $row["commentaire"] !== "" ? $row["commentaire"] : null,
                        "dateCommentaire" => isset($row["dateCommentaire"]) && $row["dateCommentaire"] !== "" ? $row["dateCommentaire"] : null,
                        "realisation_tache_id" => $realisation_tache_id,
                        "formateur_id" => $formateur_id,
                        "apprenant_id" => $apprenant_id,
                    "reference" => $row["reference"] ?? null ,
                ];
                if (!empty($row["reference"])) {
                    $commentaireRealisationTacheService->updateOrCreate(["reference" => $row["reference"]], $commentaireRealisationTacheData);
                } else {
                    $commentaireRealisationTacheService->create($commentaireRealisationTacheData);
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgRealisationTache'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'CommentaireRealisationTacheController';
        $controllerBaseName = 'commentaireRealisationTache';
        $domainName = 'CommentaireRealisationTache';

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
