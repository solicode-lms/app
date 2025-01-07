<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;

use Modules\PkgAutorisation\Models\User;
use Modules\PkgCompetences\Models\Appreciation;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgUtilisateurs\Models\Groupe;
use Modules\PkgUtilisateurs\Models\Specialite;

/**
 * Classe BaseFormateur
 * Cette classe sert de base pour le modèle Formateur.
 */
class BaseFormateur extends Model
{
    use HasFactory, HasDynamicContext;

    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'matricule', 'nom', 'prenom', 'prenom_arab', 'nom_arab', 'tele_num', 'adresse', 'diplome', 'echelle', 'echelon', 'profile_image', 'user_id'
    ];

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
        return $this->belongsToMany(Groupe::class, 'formateur_groupe');
    }
    /**
     * Relation ManyToMany pour Specialites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function specialites()
    {
        return $this->belongsToMany(Specialite::class, 'formateur_specialite');
    }

    /**
     * Relation HasMany pour Appreciations.
     *
     * @return HasMany
     */
    public function appreciations(): HasMany
    {
        return $this->hasMany(Appreciation::class, '_id', 'id');
    }
    /**
     * Relation HasMany pour Projets.
     *
     * @return HasMany
     */
    public function projets(): HasMany
    {
        return $this->hasMany(Projet::class, '_id', 'id');
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
