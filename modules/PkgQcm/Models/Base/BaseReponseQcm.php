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
use Modules\PkgQcm\Models\RealisationQcm;
use Modules\PkgQcm\Models\Question;
use Modules\PkgQcm\Models\PropositionReponse;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;

/**
 * Classe BaseReponseQcm
 * Cette classe sert de base pour le modèle ReponseQcm.
 */
class BaseReponseQcm extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'realisationQcm',
      //  'question'
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
        'reference', 'realisation_qcm_id', 'question_id', 'date_reponse'
    ];
    public $manyToMany = [
        'PropositionReponse' => ['relation' => 'propositionReponses' , "foreign_key" => "proposition_reponse_id" ],
        'RealisationUaPrototype' => ['relation' => 'realisationUaPrototypes' , "foreign_key" => "realisation_ua_prototype_id" ]
    ];
    public $manyToOne = [
        'realisationQcm' => [
            'model' => "Modules\\PkgQcm\\Models\\RealisationQcm",
            'relation' => 'realisationQcm' , 
            "foreign_key" => "realisation_qcm_id", 
            ],
        'question' => [
            'model' => "Modules\\PkgQcm\\Models\\Question",
            'relation' => 'question' , 
            "foreign_key" => "question_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour RealisationQcm.
     *
     * @return BelongsTo
     */
    public function realisationQcm(): BelongsTo
    {
        return $this->belongsTo(RealisationQcm::class, 'realisation_qcm_id', 'id');
    }
    /**
     * Relation BelongsTo pour Question.
     *
     * @return BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    /**
     * Relation ManyToMany pour PropositionReponses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function propositionReponses()
    {
        return $this->belongsToMany(PropositionReponse::class, 'proposition_reponse_reponse_qcm');
    }
    /**
     * Relation ManyToMany pour RealisationUaPrototypes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function realisationUaPrototypes()
    {
        return $this->belongsToMany(RealisationUaPrototype::class, 'realisation_ua_prototype_reponse_qcm');
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
