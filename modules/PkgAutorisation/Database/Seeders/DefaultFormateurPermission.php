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

        // Ajouter EditeurMany 
        // Tableau de configuration : modèle et type d'accès
        $permissionsMap = [
            'competence' => 'Lecteur,Extraction',
            'module' => 'Lecteur,Extraction',
            'technology' => 'Lecteur,Extraction',
            'niveauDifficulte' => 'Editeur,Extraction',
            'projet' => 'Editeur,Extraction',
            'livrable' => 'EditeurMany,Extraction',
            'resource' => 'EditeurMany,Extraction',
            'transfertCompetence' => 'EditeurMany,Extraction',
            'affectationProjet' => 'Editeur,Extraction',
            'etatsRealisationProjet' => 'Editeur,Extraction',
            'livrablesRealisation' => 'LecteurMany,Extraction',
            'realisationProjet' => 'Lecteur,Extraction',
            'validation' => 'Editeur,Extraction',
        ];

        // Actions par type d'accès
        $actionsByType = [
            'LecteurMany' => ['show'],
            'Lecteur' => ['index', 'show'],
            'Editeur' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'],
            'EditeurMany' => ['show', 'create', 'store', 'edit', 'update', 'destroy'],
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
                    $formateurRole->givePermissionTo($permission);
                }
            }
        }

        $this->command->info('Permissions associées au rôle "formateur" avec succès.');
    }
}
