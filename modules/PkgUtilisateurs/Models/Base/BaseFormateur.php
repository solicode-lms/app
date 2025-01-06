<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgCompetences\Models\Appreciation;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgUtilisateurs\Models\Groupe;
use Modules\PkgUtilisateurs\Models\Specialite;

class BaseFormateur extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['matricule', 'nom', 'prenom', 'prenom_arab', 'nom_arab', 'tele_num', 'adresse', 'diplome', 'echelle', 'echelon', 'profile_image', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'formateur_groupe');
    }
    public function specialites()
    {
        return $this->belongsToMany(Specialite::class, 'formateur_specialite');
    }


    public function appreciations()
    {
        return $this->hasMany(Appreciation::class, 'formateur_id', 'id');
    }
    public function projets()
    {
        return $this->hasMany(Projet::class, 'formateur_id', 'id');
    }

    public function __toString()
    {
        return $this->nom;
    }
}
