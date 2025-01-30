<?php
// TODO CreateOrUpdate ; utilisation d'un attribut unique : MatriculeEtudiant



namespace Modules\PkgUtilisateurs\App\Imports;

use Carbon\Carbon;
use Modules\PkgUtilisateurs\Models\ApprenantKonosy;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\PkgUtilisateurs\Models\Apprenant;
use Modules\PkgUtilisateurs\Services\ApprenantKonosyService;

class ApprenantKonosyImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return ApprenantKonosy::where('MatriculeEtudiant', $row['matriculeetudiant'])->exists();
    }

    public function model(array $row)
    {
        // Retourner null si MatriculeEtudiant est manquant
        if (empty($row['matriculeetudiant'])) {
            return null;
        }
        
        $apprenantKonosy = (new ApprenantKonosyService())->updateOrCreate(
            ['MatriculeEtudiant' => $row['matriculeetudiant']], // Critères de recherche
            [ // Données à insérer ou mettre à jour
                'Nom' => $row['nom'],
                'Prenom' => $row['prenom'],
                'Sexe' => $row['sexe'],
                'EtudiantActif' => $row['etudiantactif'],
                'Diplome' => $row['diplome'],
                'Principale' =>$row['principale']  ,
                'LibelleLong' => $row['libellelong'],
                'CodeDiplome' => $row['codediplome'],
                'DateNaissance' =>$row['datenaissance'] ,
                'DateInscription' =>  $row['dateinscription'] ,
                'LieuNaissance' => $row['lieunaissance'],
                'CIN' => $row['cin'],
                'NTelephone' => $row['ntelelephone'],
                'Adresse' => $row['adresse'],
                'Nationalite' => $row['nationalite'],
                'Nom_Arabe' => $row['nom_arabe'],
                'Prenom_Arabe' => $row['prenom_arabe'],
                'NiveauScolaire' => $row['niveauscolaire'],
            ]
        );

        return $apprenantKonosy;
    }
    
}
