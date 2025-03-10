<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Imports\Base;

use Modules\PkgGapp\Models\EDataField;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseEDataFieldImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return EDataField|null
     */
    private function findExistingRecord($reference): ?EDataField
    {
        if($reference == null) return null;
        return EDataField::where('reference', $reference)->first();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return EDataField|null
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
        return new EDataField([
             'reference' => $reference,
             'name' => $values[1] ?? null,
             'data_type' => $values[2] ?? null,
             'column_name' => $values[3] ?? null,
             'e_model_id' => $values[4] ?? null,
             'e_relationship_id' => $values[5] ?? null,
             'field_order' => $values[6] ?? null,
             'default_value' => $values[7] ?? null,
             'db_primaryKey' => $values[8] ?? null,
             'db_nullable' => $values[9] ?? null,
             'db_unique' => $values[10] ?? null,
             'calculable' => $values[11] ?? null,
             'calculable_sql' => $values[12] ?? null,
             'description' => $values[13] ?? null,
        ]);


    }
}