<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgCompetences\Models\CategorieTechnology;
use Modules\PkgCompetences\Models\Competence;

class Technology extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'categorie_technologie_id'];

    public function categorieTechnology()
    {
        return $this->belongsTo(CategorieTechnology::class, 'categorie_technologie_id', 'id');
    }

    public function competences()
    {
        return $this->belongsToMany(Competence::class, 'competence_technology');
    }

    public function __toString()
    {
        return $this->id;
    }

}
