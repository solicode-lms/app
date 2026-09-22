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
use Modules\PkgQcm\Models\QuestionLib;
use Modules\PkgQcm\Models\ReponseQcm;

/**
 * Classe BaseQuestionQcm
 * Cette classe sert de base pour le modèle QuestionQcm.
 */
class BaseQuestionQcm extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'qcm',
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
        'ordre', 'reference', 'bareme', 'qcm_id', 'question_lib_id'
    ];
    public $manyToOne = [
        'qcm' => [
            'model' => "Modules\\PkgQcm\\Models\\Qcm",
            'relation' => 'qcm' , 
            "foreign_key" => "qcm_id", 
            ],
        'questionLib' => [
            'model' => "Modules\\PkgQcm\\Models\\QuestionLib",
            'relation' => 'questionLib' , 
            "foreign_key" => "question_lib_id", 
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
     * Relation BelongsTo pour QuestionLib.
     *
     * @return BelongsTo
     */
    public function questionLib(): BelongsTo
    {
        return $this->belongsTo(QuestionLib::class, 'question_lib_id', 'id');
    }


    /**
     * Relation HasMany pour QuestionQcms.
     *
     * @return HasMany
     */
    public function reponseQcms(): HasMany
    {
        return $this->hasMany(ReponseQcm::class, 'question_qcm_id', 'id');
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
