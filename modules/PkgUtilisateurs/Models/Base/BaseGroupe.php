<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;

use Modules\PkgCompetences\Models\Filiere;
use Modules\PkgUtilisateurs\Models\Apprenant;
use Modules\PkgUtilisateurs\Models\Formateur;

/**
 * Classe BaseGroupe
 * Cette classe sert de base pour le modèle Groupe.
 */
class BaseGroupe extends Model
{
    use HasFactory, HasDynamicContext;

    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'nom', 'description', 'filiere_id'
    ];

    /**
     * Relation BelongsTo pour Filiere.
     *
     * @return BelongsTo
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'filiere_id', 'id');
    }

    /**
     * Relation ManyToMany pour Formateurs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function formateurs()
    {
        return $this->belongsToMany(Formateur::class, 'formateur_groupe');
    }

    /**
     * Relation HasMany pour Apprenants.
     *
     * @return HasMany
     */
    public function apprenants(): HasMany
    {
        return $this->hasMany(Apprenant::class, 'groupe_id', 'id');
    }

    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->code;
    }
}
