<?php

namespace Modules\PkgAutorisation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\Permission;


// php artisan db:seed --class="Modules\PkgAutorisation\Database\Seeders\DefaultFormateurPermission"
class DefaultGappPermission extends Seeder
{
    public static int $order = 1001;

    public function run(): void
    {
        $gappRole = Role::where('name', Role::GAPP_ROLE)->first();

        if (!$gappRole) {
            $this->command->error('Rôle "Gapp" introuvable.');
            return;
        }

        // Tableau de configuration : modèle et type d'accès
        $permissionsMap = [
            'EDataField' => 'Editeur,Extraction',
            'EMetadataDefinition' => 'Editeur,Extraction',
            'EMetadatum' => 'Editeur,Extraction',
            'EModel' => 'Editeur,Extraction',
            'EPackage' => 'Lecteur,Extraction',
            'ERelationship' => 'Lecteur,Extraction',
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
                    $gappRole->givePermissionTo($permission);
                }
            }
        }

        $this->command->info('Permissions associées au rôle "formateur" avec succès.');
    }
}
