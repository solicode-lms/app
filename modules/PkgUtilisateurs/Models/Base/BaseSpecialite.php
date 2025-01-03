<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgUtilisateurs\Models\Formateur;

class BaseSpecialite extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['nom', 'description'];


    public function formateurs()
    {
        return $this->belongsToMany(Formateur::class, 'formateur_specialite');
    }



    public function __toString()
    {
        return $this->nom;
    }
}
