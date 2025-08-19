<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
use Modules\PkgApprentissage\Models\EtatRealisationUa;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;
use Modules\PkgApprentissage\Models\RealisationUaProjet;

/**
 * Classe BaseRealisationUa
 * Cette classe sert de base pour le modèle RealisationUa.
 */
class BaseRealisationUa extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'uniteApprentissage',
      //  'realisationMicroCompetence',
      //  'etatRealisationUa'
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
        'unite_apprentissage_id', 'realisation_micro_competence_id', 'etat_realisation_ua_id', 'progression_cache', 'note_cache', 'bareme_cache', 'date_debut', 'date_fin', 'commentaire_formateur', 'progression_ideal_cache', 'taux_rythme_cache'
    ];
    public $manyToOne = [
        'UniteApprentissage' => [
            'model' => "Modules\\PkgCompetences\\Models\\UniteApprentissage",
            'relation' => 'uniteApprentissages' , 
            "foreign_key" => "unite_apprentissage_id", 
            ],
        'RealisationMicroCompetence' => [
            'model' => "Modules\\PkgApprentissage\\Models\\RealisationMicroCompetence",
            'relation' => 'realisationMicroCompetences' , 
            "foreign_key" => "realisation_micro_competence_id", 
            ],
        'EtatRealisationUa' => [
            'model' => "Modules\\PkgApprentissage\\Models\\EtatRealisationUa",
            'relation' => 'etatRealisationUas' , 
            "foreign_key" => "etat_realisation_ua_id", 
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
     * Relation BelongsTo pour RealisationMicroCompetence.
     *
     * @return BelongsTo
     */
    public function realisationMicroCompetence(): BelongsTo
    {
        return $this->belongsTo(RealisationMicroCompetence::class, 'realisation_micro_competence_id', 'id');
    }
    /**
     * Relation BelongsTo pour EtatRealisationUa.
     *
     * @return BelongsTo
     */
    public function etatRealisationUa(): BelongsTo
    {
        return $this->belongsTo(EtatRealisationUa::class, 'etat_realisation_ua_id', 'id');
    }


    /**
     * Relation HasMany pour RealisationUas.
     *
     * @return HasMany
     */
    public function realisationChapitres(): HasMany
    {
        return $this->hasMany(RealisationChapitre::class, 'realisation_ua_id', 'id');
    }
    /**
     * Relation HasMany pour RealisationUas.
     *
     * @return HasMany
     */
    public function realisationUaPrototypes(): HasMany
    {
        return $this->hasMany(RealisationUaPrototype::class, 'realisation_ua_id', 'id');
    }
    /**
     * Relation HasMany pour RealisationUas.
     *
     * @return HasMany
     */
    public function realisationUaProjets(): HasMany
    {
        return $this->hasMany(RealisationUaProjet::class, 'realisation_ua_id', 'id');
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
