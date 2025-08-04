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
use Modules\PkgCompetences\Models\MicroCompetence;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Modules\PkgApprentissage\Models\RealisationUa;

/**
 * Classe BaseRealisationMicroCompetence
 * Cette classe sert de base pour le modèle RealisationMicroCompetence.
 */
class BaseRealisationMicroCompetence extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'microCompetence',
      //  'apprenant',
      //  'etatRealisationMicroCompetence'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "apprenant.groupes.formateurs.user,apprenant.user";
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'micro_competence_id', 'apprenant_id', 'note_cache', 'progression_cache', 'etat_realisation_micro_competence_id', 'bareme_cache', 'commentaire_formateur', 'date_debut', 'date_fin', 'dernier_update'
    ];
    public $manyToOne = [
        'MicroCompetence' => [
            'model' => "Modules\\PkgCompetences\\Models\\MicroCompetence",
            'relation' => 'microCompetences' , 
            "foreign_key" => "micro_competence_id", 
            ],
        'Apprenant' => [
            'model' => "Modules\\PkgApprenants\\Models\\Apprenant",
            'relation' => 'apprenants' , 
            "foreign_key" => "apprenant_id", 
            ],
        'EtatRealisationMicroCompetence' => [
            'model' => "Modules\\PkgApprentissage\\Models\\EtatRealisationMicroCompetence",
            'relation' => 'etatRealisationMicroCompetences' , 
            "foreign_key" => "etat_realisation_micro_competence_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour MicroCompetence.
     *
     * @return BelongsTo
     */
    public function microCompetence(): BelongsTo
    {
        return $this->belongsTo(MicroCompetence::class, 'micro_competence_id', 'id');
    }
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
     * Relation BelongsTo pour EtatRealisationMicroCompetence.
     *
     * @return BelongsTo
     */
    public function etatRealisationMicroCompetence(): BelongsTo
    {
        return $this->belongsTo(EtatRealisationMicroCompetence::class, 'etat_realisation_micro_competence_id', 'id');
    }


    /**
     * Relation HasMany pour RealisationMicroCompetences.
     *
     * @return HasMany
     */
    public function realisationUas(): HasMany
    {
        return $this->hasMany(RealisationUa::class, 'realisation_micro_competence_id', 'id');
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
