<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgSessions\Database\Seeders\Base;

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
use Modules\PkgSessions\Models\SessionFormation;
use Modules\PkgSessions\Services\SessionFormationService;


class BaseSessionFormationSeeder extends Seeder
{
    public static int $order = 87;

    // Permissions spécifiques pour chaque type de fonctionnalité
    protected array  $featurePermissions = [
            'Afficher' => ['show','getData'],
            'Lecture' => ['index', 'show','getData'],
            'Édition sans Ajouter' => ['index', 'show','edit','update','dataCalcul','getData'],
            'Édition ' => [ 'index', 'show','create','store','edit','update','destroy','dataCalcul','getData'],
            'Extraction' => ['import', 'export'],
            'add_projet' => ['add_projet'],
            
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
        $filePath = base_path("modules/PkgSessions/Database/data/sessionFormations.csv");
        
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

        $sessionFormationService = new SessionFormationService();

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


                $sessionFormationData =[
                        "ordre" => isset($row["ordre"]) && $row["ordre"] !== "" ? $row["ordre"] : null,
                        "titre" => isset($row["titre"]) && $row["titre"] !== "" ? $row["titre"] : null,
                        "thematique" => isset($row["thematique"]) && $row["thematique"] !== "" ? $row["thematique"] : null,
                        "filiere_id" => $filiere_id,
                        "objectifs_pedagogique" => isset($row["objectifs_pedagogique"]) && $row["objectifs_pedagogique"] !== "" ? $row["objectifs_pedagogique"] : null,
                        "titre_prototype" => isset($row["titre_prototype"]) && $row["titre_prototype"] !== "" ? $row["titre_prototype"] : null,
                        "description_prototype" => isset($row["description_prototype"]) && $row["description_prototype"] !== "" ? $row["description_prototype"] : null,
                        "contraintes_prototype" => isset($row["contraintes_prototype"]) && $row["contraintes_prototype"] !== "" ? $row["contraintes_prototype"] : null,
                        "titre_projet" => isset($row["titre_projet"]) && $row["titre_projet"] !== "" ? $row["titre_projet"] : null,
                        "description_projet" => isset($row["description_projet"]) && $row["description_projet"] !== "" ? $row["description_projet"] : null,
                        "contraintes_projet" => isset($row["contraintes_projet"]) && $row["contraintes_projet"] !== "" ? $row["contraintes_projet"] : null,
                        "remarques" => isset($row["remarques"]) && $row["remarques"] !== "" ? $row["remarques"] : null,
                        "date_debut" => isset($row["date_debut"]) && $row["date_debut"] !== "" ? $row["date_debut"] : null,
                        "date_fin" => isset($row["date_fin"]) && $row["date_fin"] !== "" ? $row["date_fin"] : null,
                        "jour_feries_vacances" => isset($row["jour_feries_vacances"]) && $row["jour_feries_vacances"] !== "" ? $row["jour_feries_vacances"] : null,
                        "annee_formation_id" => $annee_formation_id,
                    "reference" => $row["reference"] ?? null ,
                ];
                if (!empty($row["reference"])) {
                    $sessionFormationService->updateOrCreate(["reference" => $row["reference"]], $sessionFormationData);
                } else {
                    $sessionFormationService->create($sessionFormationData);
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgSessions'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'SessionFormationController';
        $controllerBaseName = 'sessionFormation';
        $domainName = 'SessionFormation';

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
