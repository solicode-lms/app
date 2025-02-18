<?php

namespace Modules\PkgApprenants\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgFormation\Models\Formateur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgFormation\Services\FormateurService;

class FormateurGroupeSeeder extends Seeder
{
    public function run(): void
    {
        $this->affecterFormateurAGroupe("0001", "DMB101-2024-2025");
        $this->affecterFormateurAGroupe("0002", "DWB101-2024-2025");
        $this->affecterFormateurAGroupe("0003", "DWB102-2024-2025");
        $this->affecterFormateurAGroupe("0004", "DWB103-2024-2025");
        $this->affecterFormateurAGroupe("0005", "DWB104-2024-2025");
    }

    /**
     * Affecte un formateur à un groupe en vérifiant son existence et l'unicité de l'affectation.
     *
     * @param int $formateurId
     * @param int $groupeId
     * @return void
     */
    private function affecterFormateurAGroupe(string $formateur_reference, string $groupe_reference): void
    {
        $groupeService = new GroupeService();
        $formateurService = new FormateurService();

        // Vérification de l'existence du formateur et du groupe
        $groupe =$groupeService->getByReference($groupe_reference);
        $formateur = $formateurService->getByReference($formateur_reference);

        if (!$groupe || !$formateur) {
            $this->command->error("Erreur : Le groupe ($groupe_reference) ou le formateur ($formateur_reference) n'existe pas.");
            return;
        }

        // Vérification de l'affectation existante
        $exists = DB::table('formateur_groupe')
            ->where('groupe_id', $groupe->id)
            ->where('formateur_id', $formateur->id)
            ->exists();

        if ($exists) {
            $this->command->info("Le formateur $formateur_reference est déjà affecté au groupe  $groupe_reference.");
            return;
        }

        // Affectation avec une transaction pour éviter les incohérences
        DB::transaction(function () use ($groupe, $formateur) {
            DB::table('formateur_groupe')->insert([
                'groupe_id' => $groupe->id,
                'formateur_id' => $formateur->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
