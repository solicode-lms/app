<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgTableauBord\Database\Seeders\Base;

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
use Modules\PkgTableauBord\Models\BordereauxOfficiel;
use Modules\PkgTableauBord\Services\BordereauxOfficielService;


class BaseBordereauxOfficielSeeder extends Seeder
{
    public static int $order = 120;

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
        $filePath = base_path("modules/PkgTableauBord/Database/data/bordereauxOfficiels.csv");
        
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

        $bordereauxOfficielService = new BordereauxOfficielService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $module_id = null;
                if (!empty($row["module_reference"])) {
                    $module_id = \Modules\PkgFormation\Models\Module::where('reference', $row["module_reference"])
                        ->value('id');
                }
                $groupe_id = null;
                if (!empty($row["groupe_reference"])) {
                    $groupe_id = \Modules\PkgApprenants\Models\Groupe::where('reference', $row["groupe_reference"])
                        ->value('id');
                }
                $annee_formation_id = null;
                if (!empty($row["annee_formation_reference"])) {
                    $annee_formation_id = \Modules\PkgFormation\Models\AnneeFormation::where('reference', $row["annee_formation_reference"])
                        ->value('id');
                }
                $genere_par_user_id = null;
                if (!empty($row["genere_par_user_reference"])) {
                    $genere_par_user_id = \Modules\PkgAutorisation\Models\User::where('reference', $row["user_reference"])
                        ->value('id');
                }


                $bordereauxOfficielData =[
                        "module_id" => $module_id,
                        "groupe_id" => $groupe_id,
                        "annee_formation_id" => $annee_formation_id,
                        "nb_apprenants" => isset($row["nb_apprenants"]) && $row["nb_apprenants"] !== "" ? $row["nb_apprenants"] : null,
                        "taux_conformite_calcul" => isset($row["taux_conformite_calcul"]) && $row["taux_conformite_calcul"] !== "" ? $row["taux_conformite_calcul"] : null,
                        "statut" => isset($row["statut"]) && $row["statut"] !== "" ? $row["statut"] : null,
                        "date_scellement" => isset($row["date_scellement"]) && $row["date_scellement"] !== "" ? $row["date_scellement"] : null,
                        "hash_signature" => isset($row["hash_signature"]) && $row["hash_signature"] !== "" ? $row["hash_signature"] : null,
                        "genere_par_user_id" => $genere_par_user_id,
                    "reference" => $row["reference"] ?? null ,
                ];

                $bordereauxOfficiel = null;
                if (!empty($row["reference"])) {
                    $bordereauxOfficiel = $bordereauxOfficielService->updateOrCreate(["reference" => $row["reference"]], $bordereauxOfficielData);
                } else {
                    $bordereauxOfficiel = $bordereauxOfficielService->create($bordereauxOfficielData);
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgTableauBord'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'BordereauxOfficielController';
        $controllerBaseName = 'bordereauxOfficiel';
        $domainName = 'BordereauxOfficiel';

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
