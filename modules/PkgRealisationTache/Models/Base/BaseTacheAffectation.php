<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationTache\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationTache\Models\RealisationTache;

/**
 * Classe BaseTacheAffectation
 * Cette classe sert de base pour le modèle TacheAffectation.
 */
class BaseTacheAffectation extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'tache',
      //  'affectationProjet'
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
        'tache_id', 'affectation_projet_id', 'pourcentage_realisation_cache', 'apprenant_live_coding_cache'
    ];
    public $manyToOne = [
        'Tache' => [
            'model' => "Modules\\PkgCreationTache\\Models\\Tache",
            'relation' => 'taches' , 
            "foreign_key" => "tache_id", 
            ],
        'AffectationProjet' => [
            'model' => "Modules\\PkgRealisationProjets\\Models\\AffectationProjet",
            'relation' => 'affectationProjets' , 
            "foreign_key" => "affectation_projet_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Tache.
     *
     * @return BelongsTo
     */
    public function tache(): BelongsTo
    {
        return $this->belongsTo(Tache::class, 'tache_id', 'id');
    }
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
     * Relation HasMany pour TacheAffectations.
     *
     * @return HasMany
     */
    public function realisationTaches(): HasMany
    {
        return $this->hasMany(RealisationTache::class, 'tache_affectation_id', 'id');
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
