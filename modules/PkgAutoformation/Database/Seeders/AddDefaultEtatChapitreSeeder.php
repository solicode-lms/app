<?php

namespace Modules\PkgAutoformation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgAutoformation\Models\WorkflowChapitre;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgAutoformation\Models\EtatChapitre;
use Illuminate\Support\Facades\DB;

class AddDefaultEtatChapitreSeeder extends Seeder
{
    /**
     * Exécute les ajouts d'états de chapitre par défaut pour chaque formateur existant.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // Récupérer tous les workflows de chapitre existants avec leur couleur associée
            $workflows = WorkflowChapitre::with('sysColor')->get();

            if ($workflows->isEmpty()) {
                $this->command->info("Aucun workflow de chapitre trouvé.");
                return;
            }

            // Récupérer tous les formateurs existants
            $formateurs = Formateur::all();

            if ($formateurs->isEmpty()) {
                $this->command->info("Aucun formateur trouvé.");
                return;
            }

            // Assigner chaque état de workflow de chapitre à chaque formateur
            foreach ($formateurs as $formateur) {
                foreach ($workflows as $workflow) {
                    // Vérifier si l'état existe déjà pour éviter les doublons
                    $exists = EtatChapitre::where('formateur_id', $formateur->id)
                        ->where('workflow_chapitre_id', $workflow->id)
                        ->exists();

                    if (!$exists) {
                        EtatChapitre::create([
                            'nom' => $workflow->titre,
                            'workflow_chapitre_id' => $workflow->id,
                            'sys_color_id' => $workflow->sys_color_id ?? null, // Héritage de la couleur du workflow
                            'is_editable_only_by_formateur' => false,
                            'formateur_id' => $formateur->id,
                            'description' => $workflow->description,
                        ]);
                    }
                }
            }

            $this->command->info("Les états de chapitre par défaut ont été ajoutés pour tous les formateurs.");
        });
    }
}
