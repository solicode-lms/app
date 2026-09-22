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
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgQcm\Models\Qcm;
use Modules\PkgQcm\Models\RealisationQcm;

/**
 * Classe BaseAffectationQcmProjet
 * Cette classe sert de base pour le modèle AffectationQcmProjet.
 */
class BaseAffectationQcmProjet extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'affectationProjet',
      //  'qcm'
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
        'reference', 'affectation_projet_id', 'qcm_id'
    ];
    public $manyToOne = [
        'affectationProjet' => [
            'model' => "Modules\\PkgRealisationProjets\\Models\\AffectationProjet",
            'relation' => 'affectationProjet' , 
            "foreign_key" => "affectation_projet_id", 
            ],
        'qcm' => [
            'model' => "Modules\\PkgQcm\\Models\\Qcm",
            'relation' => 'qcm' , 
            "foreign_key" => "qcm_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour AffectationProjet.
     *
     * @return BelongsTo
     */
    public function affectationProjet(): BelongsTo
    {
        return $this->belongsTo(AffectationProjet::class, 'affectation_projet_id', 'id');
    }
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
     * Relation HasMany pour AffectationQcmProjets.
     *
     * @return HasMany
     */
    public function realisationQcms(): HasMany
    {
        return $this->hasMany(RealisationQcm::class, 'affectation_qcm_projet_id', 'id');
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
