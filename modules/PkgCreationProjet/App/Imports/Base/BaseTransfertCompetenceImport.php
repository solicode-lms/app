<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgCreationProjet\Models\TransfertCompetence;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseTransfertCompetenceImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return TransfertCompetence::where('id', $row['id'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <TransfertCompetence|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new TransfertCompetence([
            'appreciation_id' => $row['appreciation_id'],
            'competence_id' => $row['competence_id'],
            'description' => $row['description'],
            'projet_id' => $row['projet_id'],
        ]);
    }
}
