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
        return ERelationship::where('id', $row['id'])->exists();
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
            'cascade_on_delete' => $row['cascade_on_delete'],
            'description' => $row['description'],
            'source_field' => $row['source_field'],
            'source_model_id' => $row['source_model_id'],
            'target_field' => $row['target_field'],
            'target_model_id' => $row['target_model_id'],
            'type' => $row['type'],
        ]);
    }
}
