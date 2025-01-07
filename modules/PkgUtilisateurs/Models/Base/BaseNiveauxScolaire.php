<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;

use Modules\PkgUtilisateurs\Models\Apprenant;

/**
 * Classe BaseNiveauxScolaire
 * Cette classe sert de base pour le modèle NiveauxScolaire.
 */
class BaseNiveauxScolaire extends Model
{
    use HasFactory, HasDynamicContext;

    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'nom', 'description'
    ];



    /**
     * Relation HasMany pour Apprenants.
     *
     * @return HasMany
     */
    public function apprenants(): HasMany
    {
        return $this->hasMany(Apprenant::class, 'niveauxScolaire_id', 'id');
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
