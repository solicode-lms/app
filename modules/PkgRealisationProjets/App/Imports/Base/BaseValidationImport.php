<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgRealisationProjets\Models\Validation;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseValidationImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return Validation::where('reference', $row['reference'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <Validation|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new Validation([
            'note' => $row['note'],
            'message' => $row['message'],
            'is_valide' => $row['is_valide'],
            'transfert_competence_id' => $row['transfert_competence_id'],
            'realisation_projet_id' => $row['realisation_projet_id'],
            'reference' => $row['reference'],
        ]);
    }
}
