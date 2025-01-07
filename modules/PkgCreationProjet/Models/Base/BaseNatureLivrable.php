<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;

use Modules\PkgCreationProjet\Models\Livrable;

/**
 * Classe BaseNatureLivrable
 * Cette classe sert de base pour le modèle NatureLivrable.
 */
class BaseNatureLivrable extends Model
{
    use HasFactory, HasDynamicContext;

    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'description'
    ];



    /**
     * Relation HasMany pour Livrables.
     *
     * @return HasMany
     */
    public function livrables(): HasMany
    {
        return $this->hasMany(Livrable::class, 'natureLivrable_id', 'id');
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
