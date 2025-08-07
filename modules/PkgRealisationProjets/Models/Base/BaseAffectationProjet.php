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
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgApprenants\Models\SousGroupe;
use Modules\PkgFormation\Models\AnneeFormation;
use Modules\PkgEvaluateurs\Models\Evaluateur;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\TacheAffectation;

/**
 * Classe BaseAffectationProjet
 * Cette classe sert de base pour le modèle AffectationProjet.
 */
class BaseAffectationProjet extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'projet',
      //  'groupe',
      //  'sousGroupe',
      //  'anneeFormation'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "projet.formateur.user";
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'projet_id', 'groupe_id', 'sous_groupe_id', 'annee_formation_id', 'date_debut', 'date_fin', 'is_formateur_evaluateur', 'echelle_note_cible', 'description'
    ];
    public $manyToMany = [
        'Evaluateur' => ['relation' => 'evaluateurs' , "foreign_key" => "evaluateur_id" ]
    ];
    public $manyToOne = [
        'Projet' => [
            'model' => "Modules\\PkgCreationProjet\\Models\\Projet",
            'relation' => 'projets' , 
            "foreign_key" => "projet_id", 
            ],
        'Groupe' => [
            'model' => "Modules\\PkgApprenants\\Models\\Groupe",
            'relation' => 'groupes' , 
            "foreign_key" => "groupe_id", 
            ],
        'SousGroupe' => [
            'model' => "Modules\\PkgApprenants\\Models\\SousGroupe",
            'relation' => 'sousGroupes' , 
            "foreign_key" => "sous_groupe_id", 
            ],
        'AnneeFormation' => [
            'model' => "Modules\\PkgFormation\\Models\\AnneeFormation",
            'relation' => 'anneeFormations' , 
            "foreign_key" => "annee_formation_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Projet.
     *
     * @return BelongsTo
     */
    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'projet_id', 'id');
    }
    /**
     * Relation BelongsTo pour Groupe.
     *
     * @return BelongsTo
     */
    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class, 'groupe_id', 'id');
    }
    /**
     * Relation BelongsTo pour SousGroupe.
     *
     * @return BelongsTo
     */
    public function sousGroupe(): BelongsTo
    {
        return $this->belongsTo(SousGroupe::class, 'sous_groupe_id', 'id');
    }
    /**
     * Relation BelongsTo pour AnneeFormation.
     *
     * @return BelongsTo
     */
    public function anneeFormation(): BelongsTo
    {
        return $this->belongsTo(AnneeFormation::class, 'annee_formation_id', 'id');
    }

    /**
     * Relation ManyToMany pour Evaluateurs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function evaluateurs()
    {
        return $this->belongsToMany(Evaluateur::class, 'affectation_projet_evaluateur');
    }

    /**
     * Relation HasMany pour AffectationProjets.
     *
     * @return HasMany
     */
    public function realisationProjets(): HasMany
    {
        return $this->hasMany(RealisationProjet::class, 'affectation_projet_id', 'id');
    }
    /**
     * Relation HasMany pour AffectationProjets.
     *
     * @return HasMany
     */
    public function tacheAffectations(): HasMany
    {
        return $this->hasMany(TacheAffectation::class, 'affectation_projet_id', 'id');
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
