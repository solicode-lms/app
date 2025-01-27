<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgUtilisateurs\Models\Formateur;

/**
 * Classe BaseSpecialite
 * Cette classe sert de base pour le modèle Specialite.
 */
class BaseSpecialite extends BaseModel
{
    use HasFactory, HasDynamicContext;

    public function __construct() {
        parent::__construct(); 
        $this->isOwnedByUser =  false;
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'description'
    ];


    /**
     * Relation ManyToMany pour Formateurs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function formateurs()
    {
        return $this->belongsToMany(Formateur::class, 'formateur_specialite');
    }




    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nom;
    }
}
