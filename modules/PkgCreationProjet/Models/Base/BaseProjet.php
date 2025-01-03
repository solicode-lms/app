<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgCreationProjet\Models\Livrable;
use Modules\PkgCreationProjet\Models\Resource;
use Modules\PkgCreationProjet\Models\TransfertCompetence;
use Modules\PkgUtilisateurs\Models\Formateur;

class BaseProjet extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['titre', 'travail_a_faire', 'critere_de_travail', 'description', 'date_debut', 'date_fin', 'formateur_id'];

    public function formateur()
    {
        return $this->belongsTo(Formateur::class, 'formateur_id', 'id');
    }



    public function livrables()
    {
        return $this->hasMany(Livrable::class, 'projet_id', 'id');
    }
    public function resources()
    {
        return $this->hasMany(Resource::class, 'projet_id', 'id');
    }
    public function transfertCompetences()
    {
        return $this->hasMany(TransfertCompetence::class, 'projet_id', 'id');
    }

    public function __toString()
    {
        return $this->titre;
    }
}
