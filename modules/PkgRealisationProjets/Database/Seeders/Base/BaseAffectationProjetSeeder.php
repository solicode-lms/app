<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Database\Seeders\Base;

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
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;


class BaseAffectationProjetSeeder extends Seeder
{
    public static int $order = 43;

    // Permissions spécifiques pour chaque type de fonctionnalité
    protected array  $featurePermissions = [
            'Afficher' => ['show','getData'],
            'Lecture' => ['index', 'show','getData'],
            'Édition sans Ajouter' => ['index', 'show','edit','update','dataCalcul','getData'],
            'Édition ' => [ 'index', 'show','create','store','edit','update','destroy','dataCalcul','getData'],
            'Extraction' => ['import', 'export'],
            'exportPV' => ['exportPV'],
            
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
        $filePath = base_path("modules/PkgRealisationProjets/Database/data/affectationProjets.csv");
        
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

        $affectationProjetService = new AffectationProjetService();

        // Lire les données restantes en associant chaque valeur à son nom de colonne
        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {


                $projet_id = null;
                if (!empty($row["projet_reference"])) {
                    $projet_id = \Modules\PkgCreationProjet\Models\Projet::where('reference', $row["projet_reference"])
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
                $sous_groupe_id = null;
                if (!empty($row["sous_groupe_reference"])) {
                    $sous_groupe_id = \Modules\PkgApprenants\Models\SousGroupe::where('reference', $row["sous_groupe_reference"])
                        ->value('id');
                }


                $affectationProjetData =[
                        "projet_id" => $projet_id,
                        "groupe_id" => $groupe_id,
                        "annee_formation_id" => $annee_formation_id,
                        "date_debut" => !empty($row["date_debut"]) ? $row["date_debut"] : null,
                        "date_fin" => !empty($row["date_fin"]) ? $row["date_fin"] : null,
                        "sous_groupe_id" => $sous_groupe_id,
                        "is_formateur_evaluateur" => !empty($row["is_formateur_evaluateur"]) ? $row["is_formateur_evaluateur"] : null,
                        "echelle_note_cible" => !empty($row["echelle_note_cible"]) ? $row["echelle_note_cible"] : null,
                        "description" => !empty($row["description"]) ? $row["description"] : null,
                    "reference" => $row["reference"] ?? null ,
                ];
                if (!empty($row["reference"])) {
                    $affectationProjetService->updateOrCreate(["reference" => $row["reference"]], $affectationProjetData);
                } else {
                    $affectationProjetService->create($affectationProjetData);
                }
            }
        }

        fclose($csvFile);
    }


    public function addDefaultControllerDomainFeatures(): void
    {
        // Trouver dynamiquement le module SysModule par son slug
        $moduleSlug = 'PkgRealisationProjets'; // Slug du module
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            // résoudre le problème de l'ordre de chargement entre Role et SysModule
            $sysModuleSeeder =  new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        // Configuration unique pour ce contrôleur et domaine
        $controllerName = 'AffectationProjetController';
        $controllerBaseName = 'affectationProjet';
        $domainName = 'AffectationProjet';

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
