<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationTache\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgCreationTache\Models\PhaseProjet;
use Modules\PkgCompetences\Models\PhaseEvaluation;
use Modules\PkgCompetences\Models\Chapitre;
use Modules\PkgCreationProjet\Models\MobilisationUa;
use Modules\PkgCreationProjet\Models\Livrable;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Models\TacheAffectation;

/**
 * Classe BaseTache
 * Cette classe sert de base pour le modèle Tache.
 */
class BaseTache extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'projet',
      //  'phaseProjet',
      //  'phaseEvaluation',
      //  'chapitre',
      //  'mobilisationUa'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  false;
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'ordre', 'priorite', 'titre', 'projet_id', 'description', 'dateDebut', 'dateFin', 'note', 'phase_projet_id', 'is_live_coding_task', 'phase_evaluation_id', 'chapitre_id', 'mobilisation_ua_id'
    ];
    public $manyToMany = [
        'Livrable' => ['relation' => 'livrables' , "foreign_key" => "livrable_id" ]
    ];
    public $manyToOne = [
        'Projet' => [
            'model' => "Modules\\PkgCreationProjet\\Models\\Projet",
            'relation' => 'projets' , 
            "foreign_key" => "projet_id", 
            ],
        'PhaseProjet' => [
            'model' => "Modules\\PkgCreationTache\\Models\\PhaseProjet",
            'relation' => 'phaseProjets' , 
            "foreign_key" => "phase_projet_id", 
            ],
        'PhaseEvaluation' => [
            'model' => "Modules\\PkgCompetences\\Models\\PhaseEvaluation",
            'relation' => 'phaseEvaluations' , 
            "foreign_key" => "phase_evaluation_id", 
            ],
        'Chapitre' => [
            'model' => "Modules\\PkgCompetences\\Models\\Chapitre",
            'relation' => 'chapitres' , 
            "foreign_key" => "chapitre_id", 
            ],
        'MobilisationUa' => [
            'model' => "Modules\\PkgCreationProjet\\Models\\MobilisationUa",
            'relation' => 'mobilisationUas' , 
            "foreign_key" => "mobilisation_ua_id", 
            ]
    ];


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
     * Relation BelongsTo pour PhaseProjet.
     *
     * @return BelongsTo
     */
    public function phaseProjet(): BelongsTo
    {
        return $this->belongsTo(PhaseProjet::class, 'phase_projet_id', 'id');
    }
    /**
     * Relation BelongsTo pour PhaseEvaluation.
     *
     * @return BelongsTo
     */
    public function phaseEvaluation(): BelongsTo
    {
        return $this->belongsTo(PhaseEvaluation::class, 'phase_evaluation_id', 'id');
    }
    /**
     * Relation BelongsTo pour Chapitre.
     *
     * @return BelongsTo
     */
    public function chapitre(): BelongsTo
    {
        return $this->belongsTo(Chapitre::class, 'chapitre_id', 'id');
    }
    /**
     * Relation BelongsTo pour MobilisationUa.
     *
     * @return BelongsTo
     */
    public function mobilisationUa(): BelongsTo
    {
        return $this->belongsTo(MobilisationUa::class, 'mobilisation_ua_id', 'id');
    }

    /**
     * Relation ManyToMany pour Livrables.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function livrables()
    {
        return $this->belongsToMany(Livrable::class, 'livrable_tache');
    }

    /**
     * Relation HasMany pour Taches.
     *
     * @return HasMany
     */
    public function realisationTaches(): HasMany
    {
        return $this->hasMany(RealisationTache::class, 'tache_id', 'id');
    }
    /**
     * Relation HasMany pour Taches.
     *
     * @return HasMany
     */
    public function tacheAffectations(): HasMany
    {
        return $this->hasMany(TacheAffectation::class, 'tache_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->titre ?? "";
    }
}
