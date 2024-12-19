<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgCompetences\Models\Competence;

class NiveauCompetence extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'competence_id'];

    public function competence()
    {
        return $this->belongsTo(Competence::class, 'competence_id', 'id');
    }


    public function __toString()
    {
        return $this->id;
    }

}
