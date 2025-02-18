<?php

namespace Modules\PkgAutorisation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\Permission;


// php artisan db:seed --class="Modules\PkgAutorisation\Database\Seeders\DefaultFormateurPermission"
class DefaultAdminPermission extends Seeder
{
    public static int $order = 1000;

    public function run(): void
    {
        $adminRole = Role::where('name', Role::ADMIN_ROLE)->first();

        if (!$adminRole) {
            $this->command->error('Rôle "admin" introuvable.');
            return;
        }

        // Ajouter Édition 
        // Tableau de configuration : modèle et type d'accès
        $permissionsMap = [
            'profile' => 'Édition sans Ajouter',
            'apprenant' => 'Édition,initPassword',
            'formateur' => 'Édition,initPassword',
            'specialite' => 'Édition,Extraction',
            'filiere' => 'Édition sans Ajouter,Extraction',
            'competence' => 'Édition,Extraction',
            'module' => 'Édition,Extraction',
            'technology' => 'Édition,Extraction',
            'niveauDifficulte' => 'Lecture',
            'projet' => 'Lecture',
            'livrable' => 'Lecture',
            'resource' => 'Lecture',
            'transfertCompetence' => 'Lecture',
            'affectationProjet' => 'Lecture',
            'etatsRealisationProjet' => 'Lecture',
            'livrablesRealisation' => 'Lecture',
            'realisationProjet' => 'Lecture',
            'validation' => 'Lecture',
        ];

        // Actions par type d'accès
        $featurePermissions = [
            'Afficher' => ['show'],
            'Lecture' => ['index', 'show'],
            'Édition sans Ajouter' => ['index', 'show','edit','update','get<%= iModel.Names %>','dataCalcul'],
            'Édition' => [ 'index', 'show','create','store','edit','update','destroy','get<%= iModel.Names %>','dataCalcul'],
            'Extraction' => ['import', 'export'],
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
                    $adminRole->givePermissionTo($permission);
                }
            }
        }

        $this->command->info('Permissions associées au rôle "formateur" avec succès.');
    }
}
