<?php

namespace Modules\PkgAutorisation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\Permission;


// php artisan db:seed --class="Modules\PkgAutorisation\Database\Seeders\DefaultFormateurPermission"
class DefaultFormateurPermission extends Seeder
{
    public static int $order = 1000;

    public function run(): void
    {
        $formateurRole = Role::where('name', Role::FORMATEUR_ROLE)->first();

        if (!$formateurRole) {
            $this->command->error('Rôle "formateur" introuvable.');
            return;
        }

        // Ajouter Édition 
        // Tableau de configuration : modèle et type d'accès
        $permissionsMap = [
            'profile' => 'Édition sans Ajouter',
            'competence' => 'Lecture,Extraction',
            'module' => 'Lecture,Extraction',
            'technology' => 'Lecture,Extraction',
            'niveauDifficulte' => 'Édition,Extraction',
            'projet' => 'Édition,Extraction',
            'livrable' => 'Édition,Extraction',
            'resource' => 'Édition,Extraction',
            'transfertCompetence' => 'Édition,Extraction',
            'affectationProjet' => 'Édition,Extraction',
            'etatsRealisationProjet' => 'Édition,Extraction',
            'livrablesRealisation' => 'Édition,Extraction',
            'realisationProjet' => 'Édition,Extraction',
            'validation' => 'Édition,Extraction',
        ];

        // Actions par type d'accès
        $featurePermissions = [
            'Afficher' => ['show'],
            'Lecture' => ['index', 'show'],
            'Édition sans Ajouter' => ['index', 'show','edit','update','get<%= iModel.Names %>','dataCalcul'],
            'Édition' => [ 'index', 'show','create','store','edit','update','destroy','get<%= iModel.Names %>','dataCalcul'],
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
                    $formateurRole->givePermissionTo($permission);
                }
            }
        }

        $this->command->info('Permissions associées au rôle "formateur" avec succès.');
    }
}
