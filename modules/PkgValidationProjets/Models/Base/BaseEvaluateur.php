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
use Modules\PkgAutorisation\Models\User;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgValidationProjets\Models\EvaluationRealisationTache;

/**
 * Classe BaseEvaluateur
 * Cette classe sert de base pour le modèle Evaluateur.
 */
class BaseEvaluateur extends BaseModel
{
    use HasFactory, HasDynamicContext;

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
        'nom', 'prenom', 'email', 'organism', 'telephone', 'user_id'
    ];
    public $manyToMany = [
        'AffectationProjet' => ['relation' => 'affectationProjets' , "foreign_key" => "affectation_projet_id" ]
    ];
    public $manyToOne = [
        'User' => [
            'model' => "Modules\\PkgAutorisation\\Models\\User",
            'relation' => 'users' , 
            "foreign_key" => "user_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour User.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relation ManyToMany pour AffectationProjets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function affectationProjets()
    {
        return $this->belongsToMany(AffectationProjet::class, 'affectation_projet_evaluateur');
    }

    /**
     * Relation HasMany pour Evaluateurs.
     *
     * @return HasMany
     */
    public function evaluationRealisationTaches(): HasMany
    {
        return $this->hasMany(EvaluationRealisationTache::class, 'evaluateur_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nom ?? "";
    }
}
