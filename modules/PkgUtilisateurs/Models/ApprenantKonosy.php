<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprenantKonosy extends Model
{
    use HasFactory;

    protected $fillable = ['MatriculeEtudiant', 'Nom', 'Prenom', 'Sexe', 'EtudiantActif', 'Diplome', 'Principale', 'LibelleLong', 'CodeDiplome', 'DateNaissance', 'DateInscription', 'LieuNaissance', 'CIN', 'NTelephone', 'Adresse', 'Nationalite', 'Nom_Arabe', 'Prenom_Arabe', 'NiveauScolaire'];



    public function __toString()
    {
        return $this->Nom;
    }

}