<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgUtilisateurs\Models\ApprenantKonosy;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseApprenantKonosyImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return ApprenantKonosy::where('id', $row['id'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <ApprenantKonosy|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new ApprenantKonosy([
            'Adresse' => $row['Adresse'],
            'CIN' => $row['CIN'],
            'CodeDiplome' => $row['CodeDiplome'],
            'DateInscription' => $row['DateInscription'],
            'DateNaissance' => $row['DateNaissance'],
            'Diplome' => $row['Diplome'],
            'EtudiantActif' => $row['EtudiantActif'],
            'LibelleLong' => $row['LibelleLong'],
            'LieuNaissance' => $row['LieuNaissance'],
            'MatriculeEtudiant' => $row['MatriculeEtudiant'],
            'Nationalite' => $row['Nationalite'],
            'NiveauScolaire' => $row['NiveauScolaire'],
            'Nom' => $row['Nom'],
            'Nom_Arabe' => $row['Nom_Arabe'],
            'NTelephone' => $row['NTelephone'],
            'Prenom' => $row['Prenom'],
            'Prenom_Arabe' => $row['Prenom_Arabe'],
            'Principale' => $row['Principale'],
            'Sexe' => $row['Sexe'],
        ]);
    }
}
