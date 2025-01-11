<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgCompetences\Models\Appreciation;
use Modules\PkgCompetences\Models\Competence;
use Modules\PkgCompetences\Models\Technology;
use Modules\PkgCreationProjet\Models\Projet;

/**
 * Classe BaseTransfertCompetence
 * Cette classe sert de base pour le modèle TransfertCompetence.
 */
class BaseTransfertCompetence extends BaseModel
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
        'description', 'projet_id', 'competence_id', 'appreciation_id'
    ];

    /**
     * Relation BelongsTo pour Appreciation.
     *
     * @return BelongsTo
     */
    public function appreciation(): BelongsTo
    {
        return $this->belongsTo(Appreciation::class, 'appreciation_id', 'id');
    }
    /**
     * Relation BelongsTo pour Competence.
     *
     * @return BelongsTo
     */
    public function competence(): BelongsTo
    {
        return $this->belongsTo(Competence::class, 'competence_id', 'id');
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
     * Relation ManyToMany pour Technologies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'technology_transfert_competence');
    }


    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }
}
