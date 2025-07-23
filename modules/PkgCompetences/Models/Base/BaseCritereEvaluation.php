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
use Modules\PkgCompetences\Models\PhaseEvaluation;
use Modules\PkgCompetences\Models\UniteApprentissage;

/**
 * Classe BaseCritereEvaluation
 * Cette classe sert de base pour le modèle CritereEvaluation.
 */
class BaseCritereEvaluation extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'phaseEvaluation',
      //  'uniteApprentissage'
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
        'ordre', 'intitule', 'bareme', 'phase_evaluation_id', 'unite_apprentissage_id'
    ];
    public $manyToOne = [
        'PhaseEvaluation' => [
            'model' => "Modules\\PkgCompetences\\Models\\PhaseEvaluation",
            'relation' => 'phaseEvaluations' , 
            "foreign_key" => "phase_evaluation_id", 
            ],
        'UniteApprentissage' => [
            'model' => "Modules\\PkgCompetences\\Models\\UniteApprentissage",
            'relation' => 'uniteApprentissages' , 
            "foreign_key" => "unite_apprentissage_id", 
            ]
    ];


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
     * Relation BelongsTo pour UniteApprentissage.
     *
     * @return BelongsTo
     */
    public function uniteApprentissage(): BelongsTo
    {
        return $this->belongsTo(UniteApprentissage::class, 'unite_apprentissage_id', 'id');
    }





    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->intitule ?? "";
    }
}
