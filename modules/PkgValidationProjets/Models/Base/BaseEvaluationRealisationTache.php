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
use Modules\PkgGestionTaches\Models\RealisationTache;
use Modules\PkgValidationProjets\Models\Evaluateur;
use Modules\PkgValidationProjets\Models\EvaluationRealisationProjet;

/**
 * Classe BaseEvaluationRealisationTache
 * Cette classe sert de base pour le modèle EvaluationRealisationTache.
 */
class BaseEvaluationRealisationTache extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
        'realisationTache',
        'evaluateur',
        'evaluationRealisationProjet'
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
        'realisation_tache_id', 'evaluateur_id', 'note', 'message', 'evaluation_realisation_projet_id'
    ];
    public $manyToOne = [
        'RealisationTache' => [
            'model' => "Modules\\PkgGestionTaches\\Models\\RealisationTache",
            'relation' => 'realisationTaches' , 
            "foreign_key" => "realisation_tache_id", 
            ],
        'Evaluateur' => [
            'model' => "Modules\\PkgValidationProjets\\Models\\Evaluateur",
            'relation' => 'evaluateurs' , 
            "foreign_key" => "evaluateur_id", 
            ],
        'EvaluationRealisationProjet' => [
            'model' => "Modules\\PkgValidationProjets\\Models\\EvaluationRealisationProjet",
            'relation' => 'evaluationRealisationProjets' , 
            "foreign_key" => "evaluation_realisation_projet_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour RealisationTache.
     *
     * @return BelongsTo
     */
    public function realisationTache(): BelongsTo
    {
        return $this->belongsTo(RealisationTache::class, 'realisation_tache_id', 'id');
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
     * Relation BelongsTo pour EvaluationRealisationProjet.
     *
     * @return BelongsTo
     */
    public function evaluationRealisationProjet(): BelongsTo
    {
        return $this->belongsTo(EvaluationRealisationProjet::class, 'evaluation_realisation_projet_id', 'id');
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
