<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgGapp\Models\EMetadataDefinition;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseEMetadataDefinitionImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return EMetadataDefinition::where('code', $row['code'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <EMetadataDefinition|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new EMetadataDefinition([
            'code' => $row['code'],
            'default_value' => $row['default_value'],
            'description' => $row['description'],
            'groupe' => $row['groupe'],
            'name' => $row['name'],
            'scope' => $row['scope'],
            'type' => $row['type'],
        ]);
    }
}
