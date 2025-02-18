<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\App\Imports\Base;

use Modules\PkgApprenants\Models\Apprenant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseApprenantImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return Apprenant|null
     */
    private function findExistingRecord($reference): ?Apprenant
    {
        if($reference == null) return null;
        return Apprenant::where('reference', $reference)->first();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return Apprenant|null
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
        return new Apprenant([
             'nom' => $values[0] ?? null,
             'prenom' => $values[1] ?? null,
             'prenom_arab' => $values[2] ?? null,
             'nom_arab' => $values[3] ?? null,
             'profile_image' => $values[4] ?? null,
             'sexe' => $values[5] ?? null,
             'tele_num' => $values[6] ?? null,
             'diplome' => $values[7] ?? null,
             'date_naissance' => $values[8] ?? null,
             'lieu_naissance' => $values[9] ?? null,
             'cin' => $values[10] ?? null,
             'adresse' => $values[11] ?? null,
             'niveaux_scolaire_id' => $values[12] ?? null,
             'matricule' => $values[13] ?? null,
             'nationalite_id' => $values[14] ?? null,
             'actif' => $values[15] ?? null,
             'date_inscription' => $values[16] ?? null,
             'reference' => $reference,
             'user_id' => $values[18] ?? null,
        ]);


    }
}