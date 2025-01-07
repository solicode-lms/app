<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;

use Modules\PkgCreationProjet\Models\NatureLivrable;
use Modules\PkgCreationProjet\Models\Projet;

/**
 * Classe BaseLivrable
 * Cette classe sert de base pour le modèle Livrable.
 */
class BaseLivrable extends Model
{
    use HasFactory, HasDynamicContext;

    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'titre', 'nature_livrable_id', 'projet_id', 'description'
    ];

    /**
     * Relation BelongsTo pour NatureLivrable.
     *
     * @return BelongsTo
     */
    public function natureLivrable(): BelongsTo
    {
        return $this->belongsTo(NatureLivrable::class, 'nature_livrable_id', 'id');
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
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->titre;
    }
}
