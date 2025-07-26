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
use Modules\PkgCompetences\Models\PhaseEvaluation;
use Modules\PkgCompetences\Models\Chapitre;
use Modules\PkgCreationTache\Models\PrioriteTache;
use Modules\PkgCreationProjet\Models\Livrable;
use Modules\PkgRealisationTache\Models\RealisationTache;

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
      //  'phaseEvaluation',
      //  'chapitre',
      //  'prioriteTache'
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
        'ordre', 'titre', 'priorite', 'projet_id', 'description', 'dateDebut', 'dateFin', 'note', 'phase_evaluation_id', 'chapitre_id', 'priorite_tache_id'
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
        'PrioriteTache' => [
            'model' => "Modules\\PkgCreationTache\\Models\\PrioriteTache",
            'relation' => 'prioriteTaches' , 
            "foreign_key" => "priorite_tache_id", 
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
     * Relation BelongsTo pour PrioriteTache.
     *
     * @return BelongsTo
     */
    public function prioriteTache(): BelongsTo
    {
        return $this->belongsTo(PrioriteTache::class, 'priorite_tache_id', 'id');
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
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->titre ?? "";
    }
}
