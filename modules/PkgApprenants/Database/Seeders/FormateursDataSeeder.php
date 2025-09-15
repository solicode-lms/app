<?php

namespace Modules\PkgApprenants\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgFormation\Services\SpecialiteService;

class FormateursDataSeeder extends Seeder
{
    public function run(): void
    {
       
        // $groupes = [
        //     "0001" => "DMB101-2025-2026",
        //     "0002" => "DWB101-2025-2026",
        //     "0003" => "DWB102-2025-2026",
        //     "0004" => "DWB103-2025-2026",
        //     "0005" => "DWB104-2025-2026"
        // ];

        // foreach ($groupes as $formateurRef => $groupeRef) {
        //     $this->affecterFormateurAGroupe($formateurRef, $groupeRef);
        // }

        $specialites = [
            "0001" => "Développement logiciel",
            "0002" => "Développement logiciel",
            "0003" => "Développement logiciel",
            "0004" => "Développement logiciel",
            "0005" => "Développement logiciel",
        ];

        foreach ($specialites as $formateurRef => $specialiteRef) {
            $this->affecterSpecialite($formateurRef, $specialiteRef);
        }
    }

    private function affecterFormateurAGroupe(string $formateur_reference, string $groupe_reference): void
    {
        $groupeService = new GroupeService();
        $formateurService = new FormateurService();

        $groupe = $groupeService->getByReference($groupe_reference);
        $formateur = $formateurService->getByReference($formateur_reference);

        if (!$groupe || !$formateur) {
            return;
        }

        $exists = DB::table('formateur_groupe')
            ->where('groupe_id', $groupe->id)
            ->where('formateur_id', $formateur->id)
            ->exists();

        if (!$exists) {
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

    private function affecterSpecialite(string $formateur_reference, string $specialite_reference): void
    {
        $formateurService = new FormateurService();
        $specialiteService = new SpecialiteService();
    
        $formateur = $formateurService->getByReference($formateur_reference);
        $specialite = $specialiteService->getByReference($specialite_reference);
    
        if ($formateur && $specialite) {
            $formateur->specialites()->syncWithoutDetaching([$specialite->id]);
        }
    }
}