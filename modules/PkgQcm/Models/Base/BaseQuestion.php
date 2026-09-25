<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgQcm\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgQcm\Models\Qcm;
use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\PkgQcm\Models\PropositionReponse;
use Modules\PkgQcm\Models\ReponseQcm;

/**
 * Classe BaseQuestion
 * Cette classe sert de base pour le modèle Question.
 */
class BaseQuestion extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'qcm',
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
        'ordre', 'enonce', 'explication', 'type', 'is_actif', 'bareme', 'qcm_id', 'unite_apprentissage_id', 'reference'
    ];
    public $manyToOne = [
        'qcm' => [
            'model' => "Modules\\PkgQcm\\Models\\Qcm",
            'relation' => 'qcm' , 
            "foreign_key" => "qcm_id", 
            ],
        'uniteApprentissage' => [
            'model' => "Modules\\PkgCompetences\\Models\\UniteApprentissage",
            'relation' => 'uniteApprentissage' , 
            "foreign_key" => "unite_apprentissage_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Qcm.
     *
     * @return BelongsTo
     */
    public function qcm(): BelongsTo
    {
        return $this->belongsTo(Qcm::class, 'qcm_id', 'id');
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
     * Relation HasMany pour Questions.
     *
     * @return HasMany
     */
    public function propositionReponses(): HasMany
    {
        return $this->hasMany(PropositionReponse::class, 'question_id', 'id');
    }
    /**
     * Relation HasMany pour Questions.
     *
     * @return HasMany
     */
    public function reponseQcms(): HasMany
    {
        return $this->hasMany(ReponseQcm::class, 'question_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->type ?? "";
    }
}
