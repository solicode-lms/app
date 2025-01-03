<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgCompetences\Models\Filiere;
use Modules\PkgUtilisateurs\Models\Apprenant;
use Modules\PkgUtilisateurs\Models\Formateur;

class BaseGroupe extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['code', 'nom', 'description', 'filiere_id'];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'filiere_id', 'id');
    }

    public function formateurs()
    {
        return $this->belongsToMany(Formateur::class, 'formateur_groupe');
    }


    public function apprenants()
    {
        return $this->hasMany(Apprenant::class, 'groupe_id', 'id');
    }

    public function __toString()
    {
        return $this->code;
    }
}
