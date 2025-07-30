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
            'sysColor' => 'Lecture',
            'profile' => 'Édition sans Ajouter',
            'competence' => 'Lecture',
            'microCompetence' => 'Lecture',
            'uniteApprentissage' => 'Afficher',
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
            'apprenant' => 'Afficher',
            'natureLivrable' => 'Afficher',
            'projet' => 'Afficher',
            'livrable' => 'Afficher',
            'resource' => 'Afficher',
            'affectationProjet' => 'Afficher',
            'etatsRealisationProjet' => 'Afficher',
            'livrablesRealisation' => 'Afficher',
            'realisationProjet' => 'Édition sans Ajouter',
            'commentaireRealisationTache' => 'Lecture',
            'etatRealisationTache' => 'Afficher',
            'historiqueRealisationTache' => 'Lecture',
            'realisationTache' => 'Édition sans Ajouter',
            'workflowTache' => 'Afficher',
            'tache' => 'Afficher',
            'notification' => 'Lecture',
            'widget' => 'Lecture',
            'widgetUtilisateur' => 'Édition',
            'realisationChapitre' => 'Édition sans Ajouter',
            'realisationUaProjet' => 'Afficher',
            'realisationUaPrototype' => 'Afficher',
            'realisationMicroCompetence' => 'Lecture',
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
