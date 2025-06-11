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
use Modules\Core\Models\SysColor;
use Modules\PkgValidationProjets\Models\EvaluationRealisationProjet;

/**
 * Classe BaseEtatEvaluationProjet
 * Cette classe sert de base pour le modèle EtatEvaluationProjet.
 */
class BaseEtatEvaluationProjet extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'sysColor'
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
        'ordre', 'code', 'titre', 'description', 'sys_color_id'
    ];
    public $manyToOne = [
        'SysColor' => [
            'model' => "Modules\\Core\\Models\\SysColor",
            'relation' => 'sysColors' , 
            "foreign_key" => "sys_color_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour SysColor.
     *
     * @return BelongsTo
     */
    public function sysColor(): BelongsTo
    {
        return $this->belongsTo(SysColor::class, 'sys_color_id', 'id');
    }


    /**
     * Relation HasMany pour EtatEvaluationProjets.
     *
     * @return HasMany
     */
    public function evaluationRealisationProjets(): HasMany
    {
        return $this->hasMany(EvaluationRealisationProjet::class, 'etat_evaluation_projet_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->code ?? "";
    }
}
