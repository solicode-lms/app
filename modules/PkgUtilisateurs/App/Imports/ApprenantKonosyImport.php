<?php
// Modification des colonnes names par Maatwebsite



namespace Modules\PkgUtilisateurs\App\Imports;

use Carbon\Carbon;
use Modules\PkgUtilisateurs\Models\ApprenantKonosy;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\PkgUtilisateurs\Models\Apprenant;

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
        // Gestion des dates
        
        $dateNaissance = Carbon::parse(str_replace('/', '-', $row['datenaissance']))->format('Y/m/d');
        $dateInscription = Carbon::parse(str_replace('/', '-', $row['dateinscription']))->format('Y/m/d');

       
    
        // Retourner null si MatriculeEtudiant est manquant
        if (empty($row['matriculeetudiant'])) {
            return null;
        }
    
        // Rechercher ou créer un nouvel enregistrement
        $apprenantKonosy = ApprenantKonosy::updateOrCreate(
            ['MatriculeEtudiant' => $row['matriculeetudiant']], // Critères de recherche
            [ // Données à insérer ou mettre à jour
                'Nom' => $row['nom'],
                'Prenom' => $row['prenom'],
                'Sexe' => $row['sexe'],
                'EtudiantActif' => strtolower($row['etudiantactif']) === 'oui',
                'Diplome' => $row['diplome'],
                'Principale' => strtolower($row['principale']) === 'oui',
                'LibelleLong' => $row['libellelong'],
                'CodeDiplome' => $row['codediplome'],
                'DateNaissance' => $dateNaissance,
                'DateInscription' =>  $dateInscription,
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
    
        // Update Apprenant 
        Apprenant::updateOrCreate(
            ['matricule' => $row['matriculeetudiant']],
            [
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'prenom_arab' => $row['prenom_arabe'],
            'nom_arab' => $row['nom_arabe'],
            'tele_num' => $row['ntelelephone'],
            'matricule' => $row['matriculeetudiant'],
            'sexe' => $row['sexe'],
            'actif' => $apprenantKonosy->EtudiantActif,
            'diplome' => $row['diplome'],
            'date_naissance' => $apprenantKonosy->DateNaissance,
            'date_inscription' => $apprenantKonosy->DateInscription,
            'lieu_naissance' => $row['lieunaissance'],
            'cin' => $row['cin'],
            'adresse' => $row['adresse']
        ]);


        return $apprenantKonosy;
    }
    
}
