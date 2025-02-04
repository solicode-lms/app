<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgApprenants\Models\Nationalite;
use Modules\PkgApprenants\Models\NiveauxScolaire;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgRealisationProjets\Models\RealisationProjet;

/**
 * Classe BaseApprenant
 * Cette classe sert de base pour le modèle Apprenant.
 */
class BaseApprenant extends BaseModel
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
        'nom', 'prenom', 'prenom_arab', 'nom_arab', 'tele_num', 'profile_image', 'matricule', 'sexe', 'actif', 'diplome', 'date_naissance', 'date_inscription', 'lieu_naissance', 'cin', 'adresse', 'niveaux_scolaire_id', 'nationalite_id', 'user_id'
    ];

    /**
     * Relation BelongsTo pour Nationalite.
     *
     * @return BelongsTo
     */
    public function nationalite(): BelongsTo
    {
        return $this->belongsTo(Nationalite::class, 'nationalite_id', 'id');
    }
    /**
     * Relation BelongsTo pour NiveauxScolaire.
     *
     * @return BelongsTo
     */
    public function niveauxScolaire(): BelongsTo
    {
        return $this->belongsTo(NiveauxScolaire::class, 'niveaux_scolaire_id', 'id');
    }
    /**
     * Relation BelongsTo pour User.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relation ManyToMany pour Groupes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'apprenant_groupe');
    }

    /**
     * Relation HasMany pour Apprenants.
     *
     * @return HasMany
     */
    public function realisationProjets(): HasMany
    {
        return $this->hasMany(RealisationProjet::class, 'apprenant_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nom ?? "";
    }
}
