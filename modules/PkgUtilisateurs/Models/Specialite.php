<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgUtilisateurs\Models\Formateur;

class Specialite extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];


    public function formateurs()
    {
        return $this->belongsToMany(Formateur::class, 'formateur_specialite');
    }

    public function __toString()
    {
        return $this->id;
    }

}
