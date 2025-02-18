<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\App\Imports\Base;

use Modules\PkgFormation\Models\Formateur;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseFormateurImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return Formateur|null
     */
    private function findExistingRecord($reference): ?Formateur
    {
        if($reference == null) return null;
        return Formateur::where('reference', $reference)->first();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return Formateur|null
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
        return new Formateur([
             'matricule' => $values[0] ?? null,
             'nom' => $values[1] ?? null,
             'prenom' => $values[2] ?? null,
             'prenom_arab' => $values[3] ?? null,
             'nom_arab' => $values[4] ?? null,
             'email' => $values[5] ?? null,
             'tele_num' => $values[6] ?? null,
             'adresse' => $values[7] ?? null,
             'diplome' => $values[8] ?? null,
             'echelle' => $values[9] ?? null,
             'echelon' => $values[10] ?? null,
             'profile_image' => $values[11] ?? null,
             'user_id' => $values[12] ?? null,
             'reference' => $reference,
        ]);


    }
}