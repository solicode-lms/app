<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgUtilisateurs\Models\NiveauxScolaire;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseNiveauxScolaireImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return NiveauxScolaire::where('nom', $row['nom'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <NiveauxScolaire|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new NiveauxScolaire([
            'code' => $row['code'],
            'nom' => $row['nom'],
            'description' => $row['description'],
            'reference' => $row['reference'],
        ]);
    }
}
