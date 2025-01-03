<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Imports;

use Carbon\Carbon;
use Modules\PkgCreationProjet\Models\Resource;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ResourceImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return Resource::where('nom', $row['nom'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <Resource|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new Resource([
            'nom' => $row['nom'],
            'lien' => $row['lien'],
            'description' => $row['description'],
            'projet_id' => $row['projet_id'],
        ]);
    }
}
