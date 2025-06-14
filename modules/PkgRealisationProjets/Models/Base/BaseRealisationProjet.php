<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\PkgGestionTaches\Models\RealisationTache;
use Modules\PkgRealisationProjets\Models\LivrablesRealisation;
use Modules\PkgValidationProjets\Models\EvaluationRealisationProjet;
use Modules\PkgRealisationProjets\Models\Validation;

/**
 * Classe BaseRealisationProjet
 * Cette classe sert de base pour le modèle RealisationProjet.
 */
class BaseRealisationProjet extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'affectationProjet',
      //  'apprenant',
      //  'etatsRealisationProjet'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "affectationProjet.projet.formateur.user,apprenant.user";
        // Colonne dynamique : avancement_projet
        $sql = "SELECT 
          ROUND(
            (
              SELECT COUNT(*) 
              FROM realisation_taches rt
              JOIN etat_realisation_taches ert ON ert.id = rt.etat_realisation_tache_id
              JOIN workflow_taches wt ON wt.id = ert.workflow_tache_id
              WHERE rt.realisation_projet_id = realisation_projets.id
                AND wt.code IN ('TERMINEE', 'EN_VALIDATION')
            ) * 100 /
            GREATEST(1,
              (SELECT COUNT(*) 
               FROM realisation_taches rt2 
               WHERE rt2.realisation_projet_id = realisation_projets.id)
            )
          , 0)";
        static::addDynamicAttribute('avancement_projet', $sql);
        // Colonne dynamique : note
        $sql = "SELECT SUM(note)
        FROM realisation_taches
        WHERE realisation_taches.realisation_projet_id = realisation_projets.id";
        static::addDynamicAttribute('note', $sql);
        // Colonne dynamique : bareme_note
        $sql = "SELECT SUM(t.note)
                FROM realisation_taches rt
                JOIN taches t ON rt.tache_id = t.id
                WHERE rt.realisation_projet_id = realisation_projets.id
                  AND rt.note IS NOT NULL
                  AND rt.note >= 0";
        static::addDynamicAttribute('bareme_note', $sql);
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'affectation_projet_id', 'apprenant_id', 'date_debut', 'date_fin', 'etats_realisation_projet_id', 'rapport'
    ];
    public $manyToOne = [
        'AffectationProjet' => [
            'model' => "Modules\\PkgRealisationProjets\\Models\\AffectationProjet",
            'relation' => 'affectationProjets' , 
            "foreign_key" => "affectation_projet_id", 
            ],
        'Apprenant' => [
            'model' => "Modules\\PkgApprenants\\Models\\Apprenant",
            'relation' => 'apprenants' , 
            "foreign_key" => "apprenant_id", 
            ],
        'EtatsRealisationProjet' => [
            'model' => "Modules\\PkgRealisationProjets\\Models\\EtatsRealisationProjet",
            'relation' => 'etatsRealisationProjets' , 
            "foreign_key" => "etats_realisation_projet_id", 
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
     * Relation BelongsTo pour Apprenant.
     *
     * @return BelongsTo
     */
    public function apprenant(): BelongsTo
    {
        return $this->belongsTo(Apprenant::class, 'apprenant_id', 'id');
    }
    /**
     * Relation BelongsTo pour EtatsRealisationProjet.
     *
     * @return BelongsTo
     */
    public function etatsRealisationProjet(): BelongsTo
    {
        return $this->belongsTo(EtatsRealisationProjet::class, 'etats_realisation_projet_id', 'id');
    }


    /**
     * Relation HasMany pour RealisationProjets.
     *
     * @return HasMany
     */
    public function realisationTaches(): HasMany
    {
        return $this->hasMany(RealisationTache::class, 'realisation_projet_id', 'id');
    }
    /**
     * Relation HasMany pour RealisationProjets.
     *
     * @return HasMany
     */
    public function livrablesRealisations(): HasMany
    {
        return $this->hasMany(LivrablesRealisation::class, 'realisation_projet_id', 'id');
    }
    /**
     * Relation HasMany pour RealisationProjets.
     *
     * @return HasMany
     */
    public function evaluationRealisationProjets(): HasMany
    {
        return $this->hasMany(EvaluationRealisationProjet::class, 'realisation_projet_id', 'id');
    }
    /**
     * Relation HasMany pour RealisationProjets.
     *
     * @return HasMany
     */
    public function validations(): HasMany
    {
        return $this->hasMany(Validation::class, 'realisation_projet_id', 'id');
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
