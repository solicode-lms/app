<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Imports\Base;

use Modules\PkgGapp\Models\EMetadatum;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseEMetadatumImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return EMetadatum|null
     */
    private function findExistingRecord($reference): ?EMetadatum
    {
        if($reference == null) return null;
        return EMetadatum::where('reference', $reference)->first();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return EMetadatum|null
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
        return new EMetadatum([
             'Value' => $values[0] ?? null,
             'reference' => $reference,
             'value_boolean' => $values[2] ?? null,
             'value_string' => $values[3] ?? null,
             'value_integer' => $values[4] ?? null,
             'value_float' => $values[5] ?? null,
             'value_date' => $values[6] ?? null,
             'value_datetime' => $values[7] ?? null,
             'value_enum' => $values[8] ?? null,
             'value_json' => $values[9] ?? null,
             'value_text' => $values[10] ?? null,
             'e_model_id' => $values[11] ?? null,
             'e_data_field_id' => $values[12] ?? null,
             'e_metadata_definition_id' => $values[13] ?? null,
        ]);


    }
}