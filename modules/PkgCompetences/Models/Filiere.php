<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgCompetences\Models\Module;
use Modules\PkgUtilisateurs\Models\Groupe;

class Filiere extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'description'];




    public function groupes()
    {
        return $this->hasMany(Groupe::class, 'filiere_id', 'id');
    }
    public function modules()
    {
        return $this->hasMany(Module::class, 'filiere_id', 'id');
    }

    public function __toString()
    {
        return $this->code;
    }
}
