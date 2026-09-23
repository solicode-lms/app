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
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgQcm\Models\AffectationQcmProjet;
use Modules\PkgQcm\Models\QuestionQcm;
use Modules\PkgQcm\Models\RealisationQcm;

/**
 * Classe BaseQcm
 * Cette classe sert de base pour le modèle Qcm.
 */
class BaseQcm extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'formateur'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "formateur.user";
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'reference', 'titre', 'description', 'duree_minutes', 'is_duree_limitee', 'is_publie', 'formateur_id'
    ];
    public $manyToOne = [
        'formateur' => [
            'model' => "Modules\\PkgFormation\\Models\\Formateur",
            'relation' => 'formateur' , 
            "foreign_key" => "formateur_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Formateur.
     *
     * @return BelongsTo
     */
    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class, 'formateur_id', 'id');
    }


    /**
     * Relation HasMany pour Qcms.
     *
     * @return HasMany
     */
    public function affectationQcmProjets(): HasMany
    {
        return $this->hasMany(AffectationQcmProjet::class, 'qcm_id', 'id');
    }
    /**
     * Relation HasMany pour Qcms.
     *
     * @return HasMany
     */
    public function questionQcms(): HasMany
    {
        return $this->hasMany(QuestionQcm::class, 'qcm_id', 'id');
    }
    /**
     * Relation HasMany pour Qcms.
     *
     * @return HasMany
     */
    public function realisationQcms(): HasMany
    {
        return $this->hasMany(RealisationQcm::class, 'qcm_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->titre ?? "";
    }
}
