<?php

namespace Modules\PkgAutorisation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\Permission;


// php artisan db:seed --class=Modules\PkgAutorisation\Database\Seeders\DefaultGappPermission
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
            'profile' => 'Édition sans Ajouter',
            'EDataField' => 'Édition,Extraction',
            'EMetadataDefinition' => 'Édition,Extraction',
            'EMetadatum' => 'Édition,Extraction',
            'EModel' => 'Édition,Extraction',
            'EPackage' => 'Lecteur,Extraction',
            'ERelationship' => 'Lecteur,Extraction',
            'widget' => 'Lecture',
            'sectionWidget' => 'Afficher',
            'widgetUtilisateur' => 'Édition',
        ];

       // Actions par type d'accès
         $featurePermissions = [
            'Afficher' => ['show','getData'],
            'Lecture' => ['index', 'show','getData'],
            'Édition sans Ajouter' => ['index', 'show','edit','update','dataCalcul','getData'],
            'Édition' => [ 'index', 'show','create','store','edit','update','destroy','dataCalcul','getData'],
            'Extraction' => ['import', 'export'],
            'clonerProjet' => ['clonerProjet'],
            'initPassword' => ['initPassword'],
        ];

        // Actions par type d'accès
         $featurePermissions = [
            'Afficher' => ['show','getData'],
            'Lecture' => ['index', 'show','getData'],
            'Édition sans Ajouter' => ['index', 'show','edit','update','dataCalcul','getData'],
            'Édition' => [ 'index', 'show','create','store','edit','update','destroy','dataCalcul','getData'],
            'Extraction' => ['import', 'export'],
            'clonerProjet' => ['clonerProjet'],
            'initPassword' => ['initPassword'],
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
                    $gappRole->givePermissionTo($permission);
                }
            }
        }

        $this->command->info('Permissions associées au rôle "gapp" avec succès.');
    }
}
