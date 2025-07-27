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
            'competence' => 'Lecture',
            'microCompetence' => 'Lecture',
            'module' => 'Lecture',
            'anneeFormation' => 'show',
            'specialite' => 'show',
            'formateur' => 'Lecture',
            'filiere' => 'Lecture',
            'niveauxScolaire' => 'show',
            'nationalite' => 'show',
            'groupe' => 'Lecture',
            'apprenant' => 'Lecture,initPassword',
            'natureLivrable' => 'show',
            'projet' => 'Édition,Extraction,clonerProjet',
            'livrable' => 'Édition',
            'resource' => 'Édition',
            'affectationProjet' => 'Édition,Extraction',
            'etatsRealisationProjet' => 'Édition',
            'livrablesRealisation' => 'Édition',
            'realisationProjet' => 'Édition',
            'commentaireRealisationTache' => 'Édition',
            'etatRealisationTache' => 'Édition',
            'historiqueRealisationTache' => 'Édition',
            'realisationTache' => 'Édition',
            'workflowTache' => 'Afficher',
            'tache' => 'Édition',
            'notification' => 'Lecture',
            'widget' => 'Lecture',
            'widgetUtilisateur' => 'Édition',
            'realisationChapitre' => 'Édition sans Ajouter',
            'realisationUaProjet' => 'Édition sans Ajouter',
            'realisationUaPrototype' => 'Édition sans Ajouter',
            'realisationMicroCompetence' => 'Lecture',
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
                    $formateurRole->givePermissionTo($permission);
                }
            }
        }

        $this->command->info('Permissions associées au rôle "formateur" avec succès.');
    }
}
