<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgUtilisateurs\Models\Groupe;
use Modules\PkgUtilisateurs\Models\Specialite;

class Formateur extends Model
{
    use HasFactory;

    protected $fillable = ['matricule', 'nom', 'prenom', 'prenom_arab', 'nom_arab', 'tele_num', 'adresse', 'diplome', 'echelle', 'echelon', 'profile_image'];


    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'formateur_groupe');
    }
    public function specialites()
    {
        return $this->belongsToMany(Specialite::class, 'formateur_specialite');
    }

    public function __toString()
    {
        return $this->nom;
    }

}
