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
            'competence' => 'Lecteur,Extraction',
            'module' => 'Lecteur,Extraction',
            'technology' => 'Lecteur,Extraction',
            'projet' => 'Lecteur,Extraction',
            'livrable' => 'Lecteur,Extraction',
            'resource' => 'Lecteur,Extraction',
            'transfertCompetence' => 'Lecteur,Extraction',
        ];

        // Actions par type d'accès
        $actionsByType = [
            'Lecteur' => ['index', 'show'],
            'Editeur' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'],
            'Extraction' => ['export'],
        ];

        foreach ($permissionsMap as $model => $accessTypes) {
            // Diviser les types d'accès en cas de multiples valeurs
            $types = explode(',', $accessTypes);

            foreach ($types as $type) {
                $type = trim($type); // Supprimer les espaces éventuels
                $actions = $actionsByType[$type] ?? [];

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
