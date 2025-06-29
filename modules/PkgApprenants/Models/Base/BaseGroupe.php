<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgFormation\Models\Filiere;
use Modules\PkgFormation\Models\AnneeFormation;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgApprenants\Models\SousGroupe;

/**
 * Classe BaseGroupe
 * Cette classe sert de base pour le modèle Groupe.
 */
class BaseGroupe extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'filiere',
      //  'anneeFormation'
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
        'code', 'nom', 'description', 'filiere_id', 'annee_formation_id'
    ];
    public $manyToMany = [
        'Apprenant' => ['relation' => 'apprenants' , "foreign_key" => "apprenant_id" ],
        'Formateur' => ['relation' => 'formateurs' , "foreign_key" => "formateur_id" ]
    ];
    public $manyToOne = [
        'Filiere' => [
            'model' => "Modules\\PkgFormation\\Models\\Filiere",
            'relation' => 'filieres' , 
            "foreign_key" => "filiere_id", 
            ],
        'AnneeFormation' => [
            'model' => "Modules\\PkgFormation\\Models\\AnneeFormation",
            'relation' => 'anneeFormations' , 
            "foreign_key" => "annee_formation_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Filiere.
     *
     * @return BelongsTo
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'filiere_id', 'id');
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
     * Relation ManyToMany pour Apprenants.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function apprenants()
    {
        return $this->belongsToMany(Apprenant::class, 'apprenant_groupe');
    }
    /**
     * Relation ManyToMany pour Formateurs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function formateurs()
    {
        return $this->belongsToMany(Formateur::class, 'formateur_groupe');
    }

    /**
     * Relation HasMany pour Groupes.
     *
     * @return HasMany
     */
    public function affectationProjets(): HasMany
    {
        return $this->hasMany(AffectationProjet::class, 'groupe_id', 'id');
    }
    /**
     * Relation HasMany pour Groupes.
     *
     * @return HasMany
     */
    public function sousGroupes(): HasMany
    {
        return $this->hasMany(SousGroupe::class, 'groupe_id', 'id');
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
