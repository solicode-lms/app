<?php

namespace Modules\PkgAutoformation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgAutoformation\Models\WorkflowFormation;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgAutoformation\Models\EtatFormation;
use Illuminate\Support\Facades\DB;

class AddDefaultEtatSeeder extends Seeder
{

    public static int $order = 79;
    /**
     * Exécute les ajouts d'états de formation par défaut pour chaque formateur existant.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // Récupérer tous les workflows de formation existants
            $workflows = WorkflowFormation::with('sysColor')->get();

            if ($workflows->isEmpty()) {
                $this->command->info("Aucun workflow de formation trouvé.");
                return;
            }

            // Récupérer tous les formateurs existants
            $formateurs = Formateur::all();

            if ($formateurs->isEmpty()) {
                $this->command->info("Aucun formateur trouvé.");
                return;
            }

            // Assigner chaque état de workflow à chaque formateur
            foreach ($formateurs as $formateur) {
                foreach ($workflows as $workflow) {
                    // Vérifier si l'état existe déjà pour éviter les doublons
                    $exists = EtatFormation::where('formateur_id', $formateur->id)
                        ->where('workflow_formation_id', $workflow->id)
                        ->exists();

                    if (!$exists) {
                        EtatFormation::create([
                            'nom' => $workflow->titre,
                            'workflow_formation_id' => $workflow->id,
                            'sys_color_id' => $workflow->sys_color_id ?? null, // Récupération de la couleur associée au workflow
                            'is_editable_only_by_formateur' => false,
                            'formateur_id' => $formateur->id,
                            'description' => $workflow->description,
                        ]);
                    }
                }
            }

            $this->command->info("Les états de formation par défaut ont été ajoutés pour tous les formateurs.");
        });
    }
}
