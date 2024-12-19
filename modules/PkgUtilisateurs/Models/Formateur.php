<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgUtilisateurs\Models\Groupe;

class Formateur extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'prenom', 'prenom_arab', 'nom_arab', 'tele_num', 'profile_image'];


    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'formateur_groupe');
    }

    public function __toString()
    {
        return $this->id;
    }

}
