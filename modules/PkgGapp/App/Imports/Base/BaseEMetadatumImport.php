<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgGapp\Models\EMetadatum;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseEMetadatumImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return EMetadatum::where('reference', $row['reference'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <EMetadatum|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new EMetadatum([
            'reference' => $row['reference'],
            'value_boolean' => $row['value_boolean'],
            'value_string' => $row['value_string'],
            'value_integer' => $row['value_integer'],
            'value_float' => $row['value_float'],
            'value_date' => $row['value_date'],
            'value_datetime' => $row['value_datetime'],
            'value_enum' => $row['value_enum'],
            'value_json' => $row['value_json'],
            'value_text' => $row['value_text'],
            'e_model_id' => $row['e_model_id'],
            'e_data_field_id' => $row['e_data_field_id'],
            'e_metadata_definition_id' => $row['e_metadata_definition_id'],
        ]);
    }
}
