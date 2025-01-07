<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgCreationProjet\Models\TransfertCompetence;
use Modules\PkgUtilisateurs\Models\Formateur;

/**
 * Classe BaseAppreciation
 * Cette classe sert de base pour le modèle Appreciation.
 */
class BaseAppreciation extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    public function __construct() {
        parent::__construct(); 
        $this->isOwnedByUser =  true;
    }

    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'description', 'noteMin', 'noteMax', 'formateur_id'
    ];

    /**
     * Relation BelongsTo pour Formateur.
     *
     * @return BelongsTo
     */
    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class, 'formateur_id', 'id');
    }


    /**
     * Relation HasMany pour TransfertCompetences.
     *
     * @return HasMany
     */
    public function transfertCompetences(): HasMany
    {
        return $this->hasMany(TransfertCompetence::class, 'appreciation_id', 'id');
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
