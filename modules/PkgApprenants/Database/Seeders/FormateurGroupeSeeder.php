<?php

namespace Modules\PkgApprenants\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgFormation\Models\Formateur;
use Illuminate\Support\Facades\DB;

class FormateurGroupeSeeder extends Seeder
{
    public function run(): void
    {
        // ID du groupe et du formateur à affecter
        $groupeId = 1;
        $formateurId = 4;

        // Vérifier si le groupe et le formateur existent
        $groupe = Groupe::find($groupeId);
        $formateur = Formateur::find($formateurId);

        if (!$groupe) {
            $this->command->error("Le groupe avec l'ID $groupeId n'existe pas.");
            return;
        }

        if (!$formateur) {
            $this->command->error("Le formateur avec l'ID $formateurId n'existe pas.");
            return;
        }

        // Vérifier si l'affectation existe déjà
        $exists = DB::table('formateur_groupe')
            ->where('groupe_id', $groupeId)
            ->where('formateur_id', $formateurId)
            ->exists();

        if ($exists) {
            $this->command->info("Le formateur ID $formateurId est déjà affecté au groupe ID $groupeId.");
            return;
        }

        // Insérer l'affectation dans la table pivot
        DB::table('formateur_groupe')->insert([
            'groupe_id' => $groupeId,
            'formateur_id' => $formateurId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // $this->command->info("Le formateur ID $formateurId a été affecté au groupe ID $groupeId avec succès.");
    }
}
