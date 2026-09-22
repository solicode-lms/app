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
use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\PkgQcm\Models\PropositionReponse;
use Modules\PkgQcm\Models\QuestionQcm;

/**
 * Classe BaseQuestionLib
 * Cette classe sert de base pour le modèle QuestionLib.
 */
class BaseQuestionLib extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
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
        'reference', 'enonce', 'type', 'explication', 'is_actif', 'unite_apprentissage_id'
    ];
    public $manyToOne = [
        'uniteApprentissage' => [
            'model' => "Modules\\PkgCompetences\\Models\\UniteApprentissage",
            'relation' => 'uniteApprentissage' , 
            "foreign_key" => "unite_apprentissage_id", 
            ]
    ];


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
     * Relation HasMany pour QuestionLibs.
     *
     * @return HasMany
     */
    public function propositionReponses(): HasMany
    {
        return $this->hasMany(PropositionReponse::class, 'question_lib_id', 'id');
    }
    /**
     * Relation HasMany pour QuestionLibs.
     *
     * @return HasMany
     */
    public function questionQcms(): HasMany
    {
        return $this->hasMany(QuestionQcm::class, 'question_lib_id', 'id');
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
