<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgFormation\Models\Specialite;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\PkgGestionTaches\Models\CommentaireRealisationTache;
use Modules\PkgGestionTaches\Models\EtatRealisationTache;
use Modules\PkgCompetences\Models\NiveauDifficulte;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgGestionTaches\Models\LabelRealisationTache;
use Modules\PkgGestionTaches\Models\PrioriteTache;

/**
 * Classe BaseFormateur
 * Cette classe sert de base pour le modèle Formateur.
 */
class BaseFormateur extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'user'
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
        'matricule', 'nom', 'prenom', 'prenom_arab', 'nom_arab', 'email', 'tele_num', 'adresse', 'diplome', 'echelle', 'echelon', 'profile_image', 'user_id'
    ];
    public $manyToMany = [
        'Specialite' => ['relation' => 'specialites' , "foreign_key" => "specialite_id" ],
        'Groupe' => ['relation' => 'groupes' , "foreign_key" => "groupe_id" ]
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
     * Relation ManyToMany pour Specialites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function specialites()
    {
        return $this->belongsToMany(Specialite::class, 'formateur_specialite');
    }
    /**
     * Relation ManyToMany pour Groupes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'formateur_groupe');
    }

    /**
     * Relation HasMany pour Formateurs.
     *
     * @return HasMany
     */
    public function etatsRealisationProjets(): HasMany
    {
        return $this->hasMany(EtatsRealisationProjet::class, 'formateur_id', 'id');
    }
    /**
     * Relation HasMany pour Formateurs.
     *
     * @return HasMany
     */
    public function commentaireRealisationTaches(): HasMany
    {
        return $this->hasMany(CommentaireRealisationTache::class, 'formateur_id', 'id');
    }
    /**
     * Relation HasMany pour Formateurs.
     *
     * @return HasMany
     */
    public function etatRealisationTaches(): HasMany
    {
        return $this->hasMany(EtatRealisationTache::class, 'formateur_id', 'id');
    }
    /**
     * Relation HasMany pour Formateurs.
     *
     * @return HasMany
     */
    public function niveauDifficultes(): HasMany
    {
        return $this->hasMany(NiveauDifficulte::class, 'formateur_id', 'id');
    }
    /**
     * Relation HasMany pour Formateurs.
     *
     * @return HasMany
     */
    public function projets(): HasMany
    {
        return $this->hasMany(Projet::class, 'formateur_id', 'id');
    }
    /**
     * Relation HasMany pour Formateurs.
     *
     * @return HasMany
     */
    public function labelRealisationTaches(): HasMany
    {
        return $this->hasMany(LabelRealisationTache::class, 'formateur_id', 'id');
    }
    /**
     * Relation HasMany pour Formateurs.
     *
     * @return HasMany
     */
    public function prioriteTaches(): HasMany
    {
        return $this->hasMany(PrioriteTache::class, 'formateur_id', 'id');
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
