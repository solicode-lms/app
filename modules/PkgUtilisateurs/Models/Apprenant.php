<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgUtilisateurs\Models\Groupe;
use Modules\PkgUtilisateurs\Models\Nationalite;
use Modules\PkgUtilisateurs\Models\NiveauxScolaire;

class Apprenant extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['nom', 'prenom', 'prenom_arab', 'nom_arab', 'tele_num', 'profile_image', 'matricule', 'sexe', 'actif', 'diplome', 'date_naissance', 'date_inscription', 'lieu_naissance', 'cin', 'adresse', 'groupe_id', 'niveaux_scolaire_id', 'nationalite_id'];

    public function groupe()
    {
        return $this->belongsTo(Groupe::class, 'groupe_id', 'id');
    }
    public function nationalite()
    {
        return $this->belongsTo(Nationalite::class, 'nationalite_id', 'id');
    }
    public function niveauxScolaire()
    {
        return $this->belongsTo(NiveauxScolaire::class, 'niveaux_scolaire_id', 'id');
    }




    public function __toString()
    {
        return $this->nom;
    }
}
