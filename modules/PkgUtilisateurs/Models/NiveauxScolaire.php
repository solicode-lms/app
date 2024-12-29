<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgUtilisateurs\Models\Apprenant;

class NiveauxScolaire extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'description'];




    public function apprenants()
    {
        return $this->hasMany(Apprenant::class, 'niveauxScolaire_id', 'id');
    }

    public function __toString()
    {
        return $this->code;
    }
}
