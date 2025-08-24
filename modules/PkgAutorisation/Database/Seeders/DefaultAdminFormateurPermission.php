<?php

namespace Modules\PkgAutorisation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\Permission;


// php artisan db:seed --class="Modules\PkgAutorisation\Database\Seeders\DefaultFormateurPermission"
class DefaultAdminFormateurPermission extends Seeder
{
    public static int $order = 1000;

    public function run(): void
    {
        $adminFormateurRole = Role::where('name', Role::ADMIN_FORMATEUR_ROLE)->first();

        if (!$adminFormateurRole) {
            $this->command->error('Rôle "admin formateur" introuvable.');
            return;
        }

        // Ajouter Édition 
        // Tableau de configuration : modèle et type d'accès
        $permissionsMap = [
            'sysColor' => 'Afficher',
            'profile' => 'Édition sans Ajouter',
            'competence' => 'Lecture',
            'microCompetence' => 'Lecture',
            'uniteApprentissage' => 'Lecture',
            'chapitre' => 'Afficher',
            'phaseEvaluation' => 'Afficher',
            'critereEvaluation' => 'Afficher',
            'module' => 'Afficher',
            'anneeFormation' => 'Afficher',
            'specialite' => 'Afficher',
            'formateur' => 'Afficher',
            'filiere' => 'Afficher',
            'niveauxScolaire' => 'Afficher',
            'nationalite' => 'Afficher',
            'groupe' => 'Afficher',
            'sousGroupe' => 'Afficher',
            'apprenant' => 'Lecture,initPassword',
            'natureLivrable' => 'Afficher',
            'projet' => 'Lecture',
            'livrable' => 'Lecture',
            'resource' => 'Lecture',
            'mobilisationUa' => 'Lecture',
            'affectationProjet' => 'Lecture',
            'etatsRealisationProjet' => 'Lecture',
            'livrablesRealisation' => 'Lecture',
            'realisationProjet' => 'Lecture',
            'commentaireRealisationTache' => 'Lecture',
            'etatRealisationTache' => 'Lecture',
            'historiqueRealisationTache' => 'Lecture',
            'realisationTache' => 'Lecture sans Ajouter',
            'workflowTache' => 'Afficher',
            'tache' => 'Lecture',
            'notification' => 'Lecture',
            'widget' => 'Lecture',
            'sectionWidget' => 'Afficher',
            'widgetUtilisateur' => 'Édition',
            'realisationChapitre' => 'Lecture',
            'realisationUaProjet' => 'Lecture',
            'realisationUaPrototype' => 'Lecture',
            'realisationMicroCompetence' => 'Lecture',
            'realisationCompetence' => 'Lecture',
            'realisationModule' => 'Lecture',
            'etatRealisationMicroCompetence' => 'Lecture',
            'sessionFormation' => 'Édition sans Ajouter',
            'etatRealisationUa' => 'Afficher',
            'etatRealisationChapitre' => 'Afficher',
            'realisationUa' => 'Lecture',
            'alignementUa' => 'Édition',
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
                    $adminFormateurRole->givePermissionTo($permission);
                }
            }
        }

        $this->command->info('Permissions associées au rôle "formateur" avec succès.');
    }
}
