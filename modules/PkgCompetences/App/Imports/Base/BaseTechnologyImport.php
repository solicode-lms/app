<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgCompetences\Models\Technology;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseTechnologyImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return Technology::where('nom', $row['nom'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <Technology|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new Technology([
            'nom' => $row['nom'],
            'description' => $row['description'],
            'category_technology_id' => $row['category_technology_id'],
            'reference' => $row['reference'],
        ]);
    }
}
