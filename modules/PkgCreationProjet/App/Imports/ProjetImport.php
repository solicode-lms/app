<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Imports;

use Carbon\Carbon;
use Modules\PkgCreationProjet\Models\Projet;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProjetImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return Projet::where('id', $row['id'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <Projet|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new Projet([
            'titre' => $row['titre'],
            'travail_a_faire' => $row['travail_a_faire'],
            'critere_de_travail' => $row['critere_de_travail'],
            'description' => $row['description'],
            'date_debut' => $row['date_debut'],
            'date_fin' => $row['date_fin'],
            'formateur_id' => $row['formateur_id'],
        ]);
    }
}
