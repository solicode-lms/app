<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Imports;

use Carbon\Carbon;
use Modules\Core\Models\FeatureDomain;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FeatureDomainImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return FeatureDomain::where('name', $row['name'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <FeatureDomain|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new FeatureDomain([
            'name' => $row['name'],
            'slug' => $row['slug'],
            'description' => $row['description'],
            'module_id' => $row['module_id'],
        ]);
    }
}
