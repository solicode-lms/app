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
use Modules\PkgCompetences\Models\Competence;
use Modules\PkgCompetences\Models\NiveauDifficulte;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgCompetences\Models\Technology;
use Modules\PkgRealisationProjets\Models\Validation;

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
        'competence_id', 'niveau_difficulte_id', 'question', 'note', 'projet_id'
    ];
    public $manyToMany = [
        'technologies'
    ];

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
     * Relation BelongsTo pour NiveauDifficulte.
     *
     * @return BelongsTo
     */
    public function niveauDifficulte(): BelongsTo
    {
        return $this->belongsTo(NiveauDifficulte::class, 'niveau_difficulte_id', 'id');
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
     * Relation HasMany pour TransfertCompetences.
     *
     * @return HasMany
     */
    public function validations(): HasMany
    {
        return $this->hasMany(Validation::class, 'transfert_competence_id', 'id');
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
