<?php
// Ce fichier est maintenu par ESSARRAJ Fouad

namespace Modules\PkgCompetences\App\Imports\Base;

use Modules\PkgCompetences\Models\NiveauDifficulte;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseNiveauDifficulteImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return NiveauDifficulte|null
     */
    private function findExistingRecord($reference): ?NiveauDifficulte
    {
        if($reference == null) return null;
        return NiveauDifficulte::where('reference', $reference)->first();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return NiveauDifficulte|null
     */
    public function model(array $row)
    {
        // Convertir en tableau indexé pour gérer les colonnes par position
        $values = array_values($row);
        $reference = $values[5] ?? null; // La colonne "reference"


        // Vérifier si l'enregistrement existe
        $existingRecord = $this->findExistingRecord($reference);

        if ($existingRecord) {
            // Mise à jour de l'enregistrement existant
            $existingRecord->update([
                'nom' => $values[0] ?? $existingRecord->nom,
                'noteMin' => $values[1] ?? $existingRecord->noteMin,
                'noteMax' => $values[2] ?? $existingRecord->noteMax,
                'formateur_id' => $values[3] ?? $existingRecord->formateur_id,
                'description' => $values[4] ?? $existingRecord->description,
            ]);

            Log::info("Mise à jour réussie pour la référence : {$reference}");
            return null; // Retourner null pour éviter la création d'un doublon
        }

        // Création d'un nouvel enregistrement
        return new NiveauDifficulte([
            'nom' => $values[0] ?? null,
            'noteMin' => $values[1] ?? null,
            'noteMax' => $values[2] ?? null,
            'formateur_id' => $values[3] ?? null,
            'description' => $values[4] ?? null,
            'reference' => $reference,
        ]);
    }
}
