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
use Modules\PkgRealisationProjets\Models\LivrablesRealisation;
use Modules\PkgRealisationProjets\Models\Validation;
use Modules\PkgGestionTaches\Models\RealisationTache;

/**
 * Classe BaseRealisationProjet
 * Cette classe sert de base pour le modèle RealisationProjet.
 */
class BaseRealisationProjet extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "affectationProjet.projet.formateur.user,apprenant.user";
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'affectation_projet_id', 'apprenant_id', 'etats_realisation_projet_id', 'date_debut', 'date_fin', 'rapport'
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
    public function livrablesRealisations(): HasMany
    {
        return $this->hasMany(LivrablesRealisation::class, 'realisation_projet_id', 'id');
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
     * Relation HasMany pour RealisationProjets.
     *
     * @return HasMany
     */
    public function realisationTaches(): HasMany
    {
        return $this->hasMany(RealisationTache::class, 'realisation_projet_id', 'id');
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
