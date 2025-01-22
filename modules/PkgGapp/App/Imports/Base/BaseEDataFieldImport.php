<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgGapp\Models\EDataField;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseEDataFieldImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return EDataField::where('code', $row['code'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <EDataField|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new EDataField([
            'code' => $row['code'],
            'name' => $row['name'],
            'column_name' => $row['column_name'],
            'data_type' => $row['data_type'],
            'db_nullable' => $row['db_nullable'],
            'db_primaryKey' => $row['db_primaryKey'],
            'db_unique' => $row['db_unique'],
            'default_value' => $row['default_value'],
            'description' => $row['description'],
            'e_model_code' => $row['e_model_code'],
            'e_model_id' => $row['e_model_id'],
        ]);
    }
}
