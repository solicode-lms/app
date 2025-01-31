<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseRealisationProjetImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return RealisationProjet::where('reference', $row['reference'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <RealisationProjet|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new RealisationProjet([
            'date_debut' => $row['date_debut'],
            'date_fin' => $row['date_fin'],
            'rapport' => $row['rapport'],
            'projet_id' => $row['projet_id'],
            'etats_realisation_projet_id' => $row['etats_realisation_projet_id'],
            'apprenant_id' => $row['apprenant_id'],
            'affectation_projet_id' => $row['affectation_projet_id'],
            'reference' => $row['reference'],
        ]);
    }
}
