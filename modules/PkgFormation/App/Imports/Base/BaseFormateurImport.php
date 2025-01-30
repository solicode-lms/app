<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgFormation\Models\Formateur;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseFormateurImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return Formateur::where('nom', $row['nom'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <Formateur|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new Formateur([
            'matricule' => $row['matricule'],
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'prenom_arab' => $row['prenom_arab'],
            'nom_arab' => $row['nom_arab'],
            'tele_num' => $row['tele_num'],
            'adresse' => $row['adresse'],
            'diplome' => $row['diplome'],
            'echelle' => $row['echelle'],
            'echelon' => $row['echelon'],
            'profile_image' => $row['profile_image'],
            'user_id' => $row['user_id'],
            'reference' => $row['reference'],
        ]);
    }
}
