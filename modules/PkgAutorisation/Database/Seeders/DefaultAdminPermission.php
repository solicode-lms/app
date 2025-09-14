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

     
         $permissionsMap = [
            'sysColor' => 'Afficher',
            'profile' => 'Édition sans Ajouter',
            'competence' => 'Lecture',
            'microCompetence' => 'Lecture',
            'uniteApprentissage' => 'Lecture',
            'chapitre' => 'Lecture',
            'phaseEvaluation' => 'Afficher',
            'critereEvaluation' => 'Lecture',
            'module' => 'Afficher',
            'anneeFormation' => 'Afficher',
            'specialite' => 'Afficher',
            'formateur' => 'Édition,initPassword',
            'filiere' => 'Afficher',
            'niveauxScolaire' => 'Afficher',
            'nationalite' => 'Afficher',
            'groupe' => 'Édition',
            'sousGroupe' => 'Afficher',
            'apprenant' => 'Édition,initPassword',
            'natureLivrable' => 'Afficher',
            'projet' => 'Lecture,Extraction',
            'livrable' => 'Lecture',
            'resource' => 'Lecture',
            'mobilisationUa' => 'Lecture',
            'affectationProjet' => 'Lecture,Extraction',
            'etatsRealisationProjet' => 'Lecture',
            'livrablesRealisation' => 'Lecture',
            'realisationProjet' => 'Lecture',
            'commentaireRealisationTache' => 'Lecture',
            'etatRealisationTache' => 'Afficher',
            'historiqueRealisationTache' => 'Lecture',
            'realisationTache' => 'Lecture',
            'workflowTache' => 'Afficher',
            'tache' => 'Lecture',
            'notification' => 'Lecture',
            'widget' => 'Afficher',
            'sectionWidget' => 'Afficher',
            'widgetUtilisateur' => 'Édition sans Ajouter',
            'realisationChapitre' => 'Lecture',
            'realisationUaProjet' => 'Lecture',
            'realisationUaPrototype' => 'Lecture',
            'realisationMicroCompetence' => 'Lecture',
            'realisationCompetence' => 'Lecture',
            'realisationModule' => 'Lecture',
            'etatRealisationMicroCompetence' => 'Afficher',
            'sessionFormation' => 'Lecture',
            'etatRealisationUa' => 'Afficher',
            'etatRealisationChapitre' => 'Afficher',
            'realisationUa' => 'Lecture',
            'alignementUa' => 'Lecture',
            'livrableSession' => 'Lecture',
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
                    $adminRole->givePermissionTo($permission);
                }
            }
        }

        $this->command->info('Permissions associées au rôle "admin" avec succès.');
    }
}
