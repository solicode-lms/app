<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgSessions\Models\SessionFormation;

/**
 * Classe BaseAnneeFormation
 * Cette classe sert de base pour le modèle AnneeFormation.
 */
class BaseAnneeFormation extends BaseModel
{
    use HasFactory, HasDynamicContext;


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  false;
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'titre', 'date_debut', 'date_fin'
    ];




    /**
     * Relation HasMany pour AnneeFormations.
     *
     * @return HasMany
     */
    public function affectationProjets(): HasMany
    {
        return $this->hasMany(AffectationProjet::class, 'annee_formation_id', 'id');
    }
    /**
     * Relation HasMany pour AnneeFormations.
     *
     * @return HasMany
     */
    public function groupes(): HasMany
    {
        return $this->hasMany(Groupe::class, 'annee_formation_id', 'id');
    }
    /**
     * Relation HasMany pour AnneeFormations.
     *
     * @return HasMany
     */
    public function sessionFormations(): HasMany
    {
        return $this->hasMany(SessionFormation::class, 'annee_formation_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->titre ?? "";
    }
}
