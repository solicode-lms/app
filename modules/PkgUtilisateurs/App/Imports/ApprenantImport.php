<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Imports;

use Carbon\Carbon;
use Modules\PkgUtilisateurs\Models\Apprenant;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ApprenantImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return Apprenant::where('nom', $row['nom'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <Apprenant|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new Apprenant([
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'prenom_arab' => $row['prenom_arab'],
            'nom_arab' => $row['nom_arab'],
            'tele_num' => $row['tele_num'],
            'profile_image' => $row['profile_image'],
            'groupe_id' => $row['groupe_id'],
            'niveaux_scolaires_id' => $row['niveaux_scolaires_id'],
            'ville_id' => $row['ville_id'],
        ]);
    }
}
