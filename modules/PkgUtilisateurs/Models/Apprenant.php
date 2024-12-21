<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgUtilisateurs\Models\Groupe;
use Modules\PkgUtilisateurs\Models\NiveauxScolaire;
use Modules\PkgUtilisateurs\Models\Ville;

class Apprenant extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'prenom', 'prenom_arab', 'nom_arab', 'tele_num', 'profile_image', 'groupe_id', 'niveaux_scolaires_id', 'ville_id'];

    public function groupe()
    {
        return $this->belongsTo(Groupe::class, 'groupe_id', 'id');
    }
    public function niveauxScolaire()
    {
        return $this->belongsTo(NiveauxScolaire::class, 'niveaux_scolaires_id', 'id');
    }
    public function ville()
    {
        return $this->belongsTo(Ville::class, 'ville_id', 'id');
    }

    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'apprenant_groupe');
    }

    public function __toString()
    {
        return $this->id;
    }

}
