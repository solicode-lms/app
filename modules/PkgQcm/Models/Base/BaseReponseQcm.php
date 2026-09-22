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
use Modules\PkgQcm\Models\QuestionQcm;
use Modules\PkgQcm\Models\PropositionReponse;
use Modules\PkgApprentissage\Models\RealisationUaProjet;

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
      //  'questionQcm'
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
        'reference', 'realisation_qcm_id', 'question_qcm_id', 'date_reponse'
    ];
    public $manyToMany = [
        'PropositionReponse' => ['relation' => 'propositionReponses' , "foreign_key" => "proposition_reponse_id" ],
        'RealisationUaProjet' => ['relation' => 'realisationUaProjets' , "foreign_key" => "realisation_ua_projet_id" ]
    ];
    public $manyToOne = [
        'realisationQcm' => [
            'model' => "Modules\\PkgQcm\\Models\\RealisationQcm",
            'relation' => 'realisationQcm' , 
            "foreign_key" => "realisation_qcm_id", 
            ],
        'questionQcm' => [
            'model' => "Modules\\PkgQcm\\Models\\QuestionQcm",
            'relation' => 'questionQcm' , 
            "foreign_key" => "question_qcm_id", 
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
     * Relation BelongsTo pour QuestionQcm.
     *
     * @return BelongsTo
     */
    public function questionQcm(): BelongsTo
    {
        return $this->belongsTo(QuestionQcm::class, 'question_qcm_id', 'id');
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
     * Relation ManyToMany pour RealisationUaProjets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function realisationUaProjets()
    {
        return $this->belongsToMany(RealisationUaProjet::class, 'realisation_ua_projet_reponse_qcm');
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
