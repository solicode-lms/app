<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgValidationProjets\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgValidationProjets\Models\EtatEvaluationProjet;
use Modules\PkgValidationProjets\Models\Evaluateur;
use Modules\PkgValidationProjets\Models\EvaluationRealisationTache;

/**
 * Classe BaseEvaluationRealisationProjet
 * Cette classe sert de base pour le modèle EvaluationRealisationProjet.
 */
class BaseEvaluationRealisationProjet extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "evaluateur.user";
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'realisation_projet_id', 'date_evaluation', 'etat_evaluation_projet_id', 'evaluateur_id', 'remarques'
    ];
    public $manyToOne = [
        'RealisationProjet' => [
            'model' => "Modules\\PkgRealisationProjets\\Models\\RealisationProjet",
            'relation' => 'realisationProjets' , 
            "foreign_key" => "realisation_projet_id", 
            ],
        'EtatEvaluationProjet' => [
            'model' => "Modules\\PkgValidationProjets\\Models\\EtatEvaluationProjet",
            'relation' => 'etatEvaluationProjets' , 
            "foreign_key" => "etat_evaluation_projet_id", 
            ],
        'Evaluateur' => [
            'model' => "Modules\\PkgValidationProjets\\Models\\Evaluateur",
            'relation' => 'evaluateurs' , 
            "foreign_key" => "evaluateur_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour RealisationProjet.
     *
     * @return BelongsTo
     */
    public function realisationProjet(): BelongsTo
    {
        return $this->belongsTo(RealisationProjet::class, 'realisation_projet_id', 'id');
    }
    /**
     * Relation BelongsTo pour EtatEvaluationProjet.
     *
     * @return BelongsTo
     */
    public function etatEvaluationProjet(): BelongsTo
    {
        return $this->belongsTo(EtatEvaluationProjet::class, 'etat_evaluation_projet_id', 'id');
    }
    /**
     * Relation BelongsTo pour Evaluateur.
     *
     * @return BelongsTo
     */
    public function evaluateur(): BelongsTo
    {
        return $this->belongsTo(Evaluateur::class, 'evaluateur_id', 'id');
    }


    /**
     * Relation HasMany pour EvaluationRealisationProjets.
     *
     * @return HasMany
     */
    public function evaluationRealisationTaches(): HasMany
    {
        return $this->hasMany(EvaluationRealisationTache::class, 'evaluation_realisation_projet_id', 'id');
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
