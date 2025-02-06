<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseAffectationProjetImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return AffectationProjet::where('reference', $row['reference'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <AffectationProjet|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new AffectationProjet([
            'groupe_id' => $row['groupe_id'],
            'date_debut' => $row['date_debut'],
            'date_fin' => $row['date_fin'],
            'projet_id' => $row['projet_id'],
            'description' => $row['description'],
            'reference' => $row['reference'],
            'annee_formation_id' => $row['annee_formation_id'],
        ]);
    }
}
