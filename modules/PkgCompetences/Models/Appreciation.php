<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgUtilisateurs\Models\Formateur;

class Appreciation extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'noteMin', 'noteMax', 'formateur_id'];

    public function formateur()
    {
        return $this->belongsTo(Formateur::class, 'formateur_id', 'id');
    }


    public function __toString()
    {
        return $this->nom;
    }

}
