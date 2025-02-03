<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgGapp\Models\ERelationship;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseERelationshipImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return ERelationship::where('reference', $row['reference'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <ERelationship|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new ERelationship([
            'reference' => $row['reference'],
            'name' => $row['name'],
            'type' => $row['type'],
            'source_e_model_id' => $row['source_e_model_id'],
            'target_e_model_id' => $row['target_e_model_id'],
            'cascade_on_delete' => $row['cascade_on_delete'],
            'is_cascade' => $row['is_cascade'],
            'description' => $row['description'],
            'column_name' => $row['column_name'],
            'referenced_table' => $row['referenced_table'],
            'referenced_column' => $row['referenced_column'],
            'through' => $row['through'],
            'with_column' => $row['with_column'],
            'morph_name' => $row['morph_name'],
        ]);
    }
}
