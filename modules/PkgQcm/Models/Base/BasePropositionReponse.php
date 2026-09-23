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
use Modules\PkgQcm\Models\Question;
use Modules\PkgQcm\Models\ReponseQcm;

/**
 * Classe BasePropositionReponse
 * Cette classe sert de base pour le modèle PropositionReponse.
 */
class BasePropositionReponse extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'questionLib'
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
        'ordre', 'reference', 'libelle', 'is_correcte', 'question_lib_id'
    ];
    public $manyToMany = [
        'ReponseQcm' => ['relation' => 'reponseQcms' , "foreign_key" => "reponse_qcm_id" ]
    ];
    public $manyToOne = [
        'questionLib' => [
            'model' => "Modules\\PkgQcm\\Models\\Question",
            'relation' => 'questionLib' , 
            "foreign_key" => "question_lib_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Question.
     *
     * @return BelongsTo
     */
    public function questionLib(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_lib_id', 'id');
    }

    /**
     * Relation ManyToMany pour ReponseQcms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reponseQcms()
    {
        return $this->belongsToMany(ReponseQcm::class, 'proposition_reponse_reponse_qcm');
    }




    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->libelle ?? "";
    }
}
