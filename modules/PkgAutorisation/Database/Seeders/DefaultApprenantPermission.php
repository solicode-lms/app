<?php

namespace Modules\PkgAutorisation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\Permission;


// php artisan db:seed --class="Modules\PkgAutorisation\Database\Seeders\DefaultFormateurPermission"
class DefaultApprenantPermission extends Seeder
{
    public static int $order = 1002;

    public function run(): void
    {
        $apprenantRole = Role::where('name', Role::APPRENANT_ROLE)->first();

        if (!$apprenantRole) {
            $this->command->error('Rôle "apprenant" introuvable.');
            return;
        }

        // Tableau de configuration : modèle et type d'accès
        $permissionsMap = [
            'profile' => 'Édition sans Ajouter',
            'competence' => 'Lecture',
            'module' => 'Afficher',
            'technology' => 'Afficher',
            'livrable' => 'Afficher',
            'resource' => 'Afficher',
            'transfertCompetence' => 'Lecture',
            'realisationProjet' => 'Édition sans Ajouter',
            'livrablesRealisation' => 'Édition',
            'validation' => 'Lecture',
        ];

        // Actions par type d'accès
        $featurePermissions = [
            'Afficher' => ['show'],
            'Lecture' => ['index', 'show'],
            'Édition sans Ajouter' => ['index', 'show','edit','update','dataCalcul'],
            'Édition' => [ 'index', 'show','create','store','edit','update','destroy','dataCalcul'],
            'Extraction' => ['import', 'export'],
        ];


        foreach ($permissionsMap as $model => $accessTypes) {
            // Diviser les types d'accès en cas de multiples valeurs
            $types = explode(',', $accessTypes);

            foreach ($types as $type) {
                $type = trim($type); // Supprimer les espaces éventuels
                $actions = $featurePermissions[$type] ?? [];

                foreach ($actions as $action) {
                    // Nom de la permission : action + modèle
                    $permissionName = strtolower("$action-$model");

                    // Vérifier ou créer la permission
                    $permission = Permission::where('name', $permissionName)->first();

                    // Associer la permission au rôle "formateur"
                    $apprenantRole->givePermissionTo($permission);
                }
            }
        }

        $this->command->info('Permissions associées au rôle "apprenant" avec succès.');
    }
}
