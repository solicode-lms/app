<?php
// TODO : add hasmany


namespace Modules\PkgCreationProjet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgUtilisateurs\Models\Formateur;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'travail_a_faire', 'critere_de_travail', 'description', 'date_debut', 'date_fin', 'formateur_id'];

    public function formateur()
    {
        return $this->belongsTo(Formateur::class, 'formateur_id', 'id');
    }


    public function resources()
    {
        return $this->hasMany(Resource::class, 'projet_id', 'id');
    }


    public function livrables()
    {
        return $this->hasMany(Livrable::class, 'projet_id', 'id');
    }

    public function transfert_competences()
    {
        return $this->hasMany(TransfertCompetence::class, 'projet_id', 'id');
    }

    public function __toString()
    {
        return $this->titre;
    }

}