<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgFormation\Models\AnneeFormation;
use Modules\PkgRealisationProjets\Models\RealisationProjet;

/**
 * Classe BaseAffectationProjet
 * Cette classe sert de base pour le modèle AffectationProjet.
 */
class BaseAffectationProjet extends BaseModel
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
        'groupe_id', 'date_debut', 'date_fin', 'projet_id', 'description', 'annee_formation_id'
    ];

    /**
     * Relation BelongsTo pour Groupe.
     *
     * @return BelongsTo
     */
    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class, 'groupe_id', 'id');
    }
    /**
     * Relation BelongsTo pour Projet.
     *
     * @return BelongsTo
     */
    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'projet_id', 'id');
    }
    /**
     * Relation BelongsTo pour AnneeFormation.
     *
     * @return BelongsTo
     */
    public function anneeFormation(): BelongsTo
    {
        return $this->belongsTo(AnneeFormation::class, 'annee_formation_id', 'id');
    }


    /**
     * Relation HasMany pour AffectationProjets.
     *
     * @return HasMany
     */
    public function realisationProjets(): HasMany
    {
        return $this->hasMany(RealisationProjet::class, 'affectation_projet_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->id ?? "";
    }
}
