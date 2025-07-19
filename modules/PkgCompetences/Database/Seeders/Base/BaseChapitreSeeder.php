<?php
// Ce fichier est maintenu par ESSARRAJ Fouad

namespace Modules\PkgCompetences\Database\Seeders\Base;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Database\Seeders\SysModuleSeeder;
use Modules\Core\Models\Feature;
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Models\SysController;
use Modules\Core\Models\SysModule;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgCompetences\Services\ChapitreService;

class BaseChapitreSeeder extends Seeder
{
    public static int $order = 76;

    protected array $featurePermissions = [
        'Afficher' => ['show','getData'],
        'Lecture' => ['index', 'show','getData'],
        'Édition sans Ajouter' => ['index', 'show','edit','update','dataCalcul','getData'],
        'Édition ' => [ 'index', 'show','create','store','edit','update','destroy','dataCalcul','getData'],
        'Extraction' => ['import', 'export'],
    ];

    public function run(): void
    {
        $this->seedFromCsv();
        $this->addDefaultControllerDomainFeatures();
    }

    public function seedFromCsv(): void
    {
        $filePath = base_path("modules/PkgCompetences/Database/data/chapitres.csv");
        if (!file_exists($filePath) || filesize($filePath) === 0) return;

        $csvFile = fopen($filePath, "r");
        if (!$csvFile) return;

        $headers = fgetcsv($csvFile);
        if (!$headers) {
            fclose($csvFile);
            return;
        }

        $chapitreService = new ChapitreService();

        while (($data = fgetcsv($csvFile)) !== false) {
            $row = array_combine($headers, $data);
            if ($row) {
                // Conversion des références en IDs
                $uniteApprentissageId = null;
                if (!empty($row["unite_apprentissage_reference"])) {
                    $uniteApprentissageId = \Modules\PkgCompetences\Models\UniteApprentissage::where('reference', $row["unite_apprentissage_reference"])
                        ->value('id');
                }

                $formateurId = null;
                if (!empty($row["formateur_reference"])) {
                    $formateurId = \Modules\PkgFormation\Models\Formateur::where('reference', $row["formateur_reference"])
                        ->value('id');
                }

                $chapitreData = [
                    "ordre" => $row["ordre"] ?? null,
                    "code" => $row["code"] ?? null,
                    "nom" => $row["nom"] ?? null,
                    "lien" => $row["lien"] ?? null,
                    "description" => $row["description"] ?? null,
                    "duree_en_heure" => $row["duree_en_heure"] ?? null,
                    "isOfficiel" => $row["isOfficiel"] ?? null,
                    "unite_apprentissage_id" => $uniteApprentissageId,
                    "formateur_id" => $formateurId,
                    "reference" => $row["reference"] ?? Str::uuid()->toString(),
                ];

                if (!empty($row["reference"])) {
                    $chapitreService->updateOrCreate(["reference" => $row["reference"]], $chapitreData);
                } else {
                    $chapitreService->create($chapitreData);
                }
            }
        }
        fclose($csvFile);
    }

    public function addDefaultControllerDomainFeatures(): void
    {
        $moduleSlug = 'PkgCompetences';
        $sysModule = SysModule::where('slug', $moduleSlug)->first();

        if (!$sysModule) {
            $sysModuleSeeder = new SysModuleSeeder();
            $sysModuleSeeder->seedFromCsv();
            $sysModule = SysModule::where('slug', $moduleSlug)->first();
        }

        $controllerName = 'ChapitreController';
        $controllerBaseName = 'chapitre';
        $domainName = 'Chapitre';

        $sysController = SysController::firstOrCreate(
            ['name' => $controllerName],
            [
                'slug' => Str::slug($controllerName),
                'description' => "Controller for $domainName",
                'sys_module_id' => $sysModule->id,
            ]
        );

        $featureDomain = FeatureDomain::firstOrCreate(
            ['slug' => Str::slug($domainName)],
            [
                'name' => $domainName,
                'description' => "Gestion des $domainName",
                'sys_module_id' => $sysModule->id,
            ]
        );

        foreach ($this->featurePermissions as $featureName => $actions) {
            $feature = Feature::firstOrCreate(
                ['name' => "$domainName - $featureName"],
                [
                    'description' => "Feature $featureName for $domainName",
                    'feature_domain_id' => $featureDomain->id,
                ]
            );

            $permissionIds = [];
            foreach ($actions as $action) {
                $permission = Permission::firstOrCreate(
                    ['name' => "$action-$controllerBaseName"],
                    [
                        'guard_name' => 'web',
                        'controller_id' => $sysController->id,
                    ]
                );
                $permissionIds[] = $permission->id;
            }

            $feature->permissions()->syncWithoutDetaching($permissionIds);
        }
    }
}
