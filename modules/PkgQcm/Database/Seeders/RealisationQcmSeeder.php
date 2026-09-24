<?php

namespace Modules\PkgQcm\Database\Seeders;

use Modules\PkgQcm\Database\Seeders\Base\BaseRealisationQcmSeeder;
use Modules\PkgAutorisation\Models\Permission;
use Modules\Core\Models\Feature;
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Models\SysController;

class RealisationQcmSeeder extends BaseRealisationQcmSeeder
{
    public function run(): void
    {
        // Exécuter le seeder de base (CSV + permissions CRUD)
        parent::run();

        // Ajouter la permission spécifique manquante pour le passage de QCM
        $sysController = SysController::where('name', 'RealisationQcmController')->first();
        $permission = Permission::firstOrCreate(
            ['name' => 'passer-qcm'],
            [
                'guard_name' => 'web',
                'controller_id' => $sysController ? $sysController->id : null
            ]
        );

        // Associer la permission à une Feature pour pouvoir l'attribuer aux rôles (ex: Apprenant)
        $featureDomain = FeatureDomain::where('slug', 'realisationqcm')->first();
        if ($featureDomain) {
            $feature = Feature::firstOrCreate(
                ['name' => 'RealisationQcm - Passage QCM'],
                [
                    'description' => 'Permet à l\'apprenant de passer le QCM (Interface V2)',
                    'feature_domain_id' => $featureDomain->id,
                ]
            );
            $feature->permissions()->syncWithoutDetaching([$permission->id]);
        }
    }
}
