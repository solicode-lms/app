<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgCompetences\Models\Competence;
use Modules\PkgCompetences\Models\Filiere;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'masse_horaire', 'filiere_id'];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'filiere_id', 'id');
    }



    public function competences()
    {
        return $this->hasMany(Competence::class, 'module_id', 'id');
    }

    public function __toString()
    {
        return $this->nom;
    }
}
