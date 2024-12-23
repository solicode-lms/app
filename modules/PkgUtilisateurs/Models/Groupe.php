<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgUtilisateurs\Models\Apprenant;
use Modules\PkgUtilisateurs\Models\Formateur;

class Groupe extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];


    public function apprenants()
    {
        return $this->belongsToMany(Apprenant::class, 'apprenant_groupe');
    }
    public function formateurs()
    {
        return $this->belongsToMany(Formateur::class, 'formateur_groupe');
    }

    public function __toString()
    {
        return $this->nom;
    }

}
