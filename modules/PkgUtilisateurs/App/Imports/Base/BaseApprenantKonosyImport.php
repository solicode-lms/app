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
            'MatriculeEtudiant' => $row['MatriculeEtudiant'],
            'Nom' => $row['Nom'],
            'Prenom' => $row['Prenom'],
            'Sexe' => $row['Sexe'],
            'EtudiantActif' => $row['EtudiantActif'],
            'Diplome' => $row['Diplome'],
            'Principale' => $row['Principale'],
            'LibelleLong' => $row['LibelleLong'],
            'CodeDiplome' => $row['CodeDiplome'],
            'DateNaissance' => $row['DateNaissance'],
            'DateInscription' => $row['DateInscription'],
            'LieuNaissance' => $row['LieuNaissance'],
            'CIN' => $row['CIN'],
            'NTelephone' => $row['NTelephone'],
            'Adresse' => $row['Adresse'],
            'Nationalite' => $row['Nationalite'],
            'Nom_Arabe' => $row['Nom_Arabe'],
            'Prenom_Arabe' => $row['Prenom_Arabe'],
            'NiveauScolaire' => $row['NiveauScolaire'],
        ]);
    }
}
