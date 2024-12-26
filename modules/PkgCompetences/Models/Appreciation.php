<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgCompetences\Models\NiveauCompetence;
use Modules\PkgUtilisateurs\Models\Formateur;

class Appreciation extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'noteMin', 'noteMax', 'niveau_competence_id', 'formateur_id'];

    public function formateur()
    {
        return $this->belongsTo(Formateur::class, 'formateur_id', 'id');
    }
    public function niveauCompetence()
    {
        return $this->belongsTo(NiveauCompetence::class, 'niveau_competence_id', 'id');
    }


    public function __toString()
    {
        return $this->nom;
    }

}
