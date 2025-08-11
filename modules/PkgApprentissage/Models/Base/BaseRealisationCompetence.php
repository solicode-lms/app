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
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprentissage\Models\RealisationModule;
use Modules\PkgCompetences\Models\Competence;
use Modules\PkgApprentissage\Models\EtatRealisationCompetence;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;

/**
 * Classe BaseRealisationCompetence
 * Cette classe sert de base pour le modèle RealisationCompetence.
 */
class BaseRealisationCompetence extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'apprenant',
      //  'realisationModule',
      //  'competence',
      //  'etatRealisationCompetence'
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
        'date_debut', 'date_fin', 'progression_cache', 'note_cache', 'bareme_cache', 'commentaire_formateur', 'dernier_update', 'apprenant_id', 'realisation_module_id', 'competence_id', 'etat_realisation_competence_id'
    ];
    public $manyToOne = [
        'Apprenant' => [
            'model' => "Modules\\PkgApprenants\\Models\\Apprenant",
            'relation' => 'apprenants' , 
            "foreign_key" => "apprenant_id", 
            ],
        'RealisationModule' => [
            'model' => "Modules\\PkgApprentissage\\Models\\RealisationModule",
            'relation' => 'realisationModules' , 
            "foreign_key" => "realisation_module_id", 
            ],
        'Competence' => [
            'model' => "Modules\\PkgCompetences\\Models\\Competence",
            'relation' => 'competences' , 
            "foreign_key" => "competence_id", 
            ],
        'EtatRealisationCompetence' => [
            'model' => "Modules\\PkgApprentissage\\Models\\EtatRealisationCompetence",
            'relation' => 'etatRealisationCompetences' , 
            "foreign_key" => "etat_realisation_competence_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Apprenant.
     *
     * @return BelongsTo
     */
    public function apprenant(): BelongsTo
    {
        return $this->belongsTo(Apprenant::class, 'apprenant_id', 'id');
    }
    /**
     * Relation BelongsTo pour RealisationModule.
     *
     * @return BelongsTo
     */
    public function realisationModule(): BelongsTo
    {
        return $this->belongsTo(RealisationModule::class, 'realisation_module_id', 'id');
    }
    /**
     * Relation BelongsTo pour Competence.
     *
     * @return BelongsTo
     */
    public function competence(): BelongsTo
    {
        return $this->belongsTo(Competence::class, 'competence_id', 'id');
    }
    /**
     * Relation BelongsTo pour EtatRealisationCompetence.
     *
     * @return BelongsTo
     */
    public function etatRealisationCompetence(): BelongsTo
    {
        return $this->belongsTo(EtatRealisationCompetence::class, 'etat_realisation_competence_id', 'id');
    }


    /**
     * Relation HasMany pour RealisationCompetences.
     *
     * @return HasMany
     */
    public function realisationMicroCompetences(): HasMany
    {
        return $this->hasMany(RealisationMicroCompetence::class, 'realisation_competence_id', 'id');
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
