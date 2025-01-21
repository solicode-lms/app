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
        return EMetadatum::where('code', $row['code'])->exists();
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
            'code' => $row['code'],
            'value_boolean' => $row['value_boolean'],
            'value_string' => $row['value_string'],
            'value_int' => $row['value_int'],
            'value_object' => $row['value_object'],
            'object_id' => $row['object_id'],
            'object_type' => $row['object_type'],
            'e_metadata_definition_id' => $row['e_metadata_definition_id'],
            'EModel' => $row['EModel'],
            'EDataField' => $row['EDataField'],
        ]);
    }
}
