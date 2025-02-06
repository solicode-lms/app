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
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\PkgRealisationProjets\Models\Validation;

/**
 * Classe BaseRealisationProjet
 * Cette classe sert de base pour le modèle RealisationProjet.
 */
class BaseRealisationProjet extends BaseModel
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
        'date_debut', 'date_fin', 'rapport', 'etats_realisation_projet_id', 'apprenant_id', 'affectation_projet_id'
    ];

    /**
     * Relation BelongsTo pour AffectationProjet.
     *
     * @return BelongsTo
     */
    public function affectationProjet(): BelongsTo
    {
        return $this->belongsTo(AffectationProjet::class, 'affectation_projet_id', 'id');
    }
    /**
     * Relation BelongsTo pour Apprenant.
     *
     * @return BelongsTo
     */
    public function apprenant(): BelongsTo
    {
        return $this->belongsTo(Apprenant::class, 'apprenant_id', 'id');
    }
    /**
     * Relation BelongsTo pour EtatsRealisationProjet.
     *
     * @return BelongsTo
     */
    public function etatsRealisationProjet(): BelongsTo
    {
        return $this->belongsTo(EtatsRealisationProjet::class, 'etats_realisation_projet_id', 'id');
    }


    /**
     * Relation HasMany pour RealisationProjets.
     *
     * @return HasMany
     */
    public function validations(): HasMany
    {
        return $this->hasMany(Validation::class, 'realisation_projet_id', 'id');
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
