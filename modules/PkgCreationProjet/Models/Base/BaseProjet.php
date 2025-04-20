<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgFormation\Models\Filiere;
use Modules\PkgCreationProjet\Models\TransfertCompetence;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgGestionTaches\Models\Tache;
use Modules\PkgCreationProjet\Models\Livrable;
use Modules\PkgCreationProjet\Models\Resource;

/**
 * Classe BaseProjet
 * Cette classe sert de base pour le modèle Projet.
 */
class BaseProjet extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "formateur.user,affectationProjets.realisationProjets.apprenant.user";
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'titre', 'formateur_id', 'travail_a_faire', 'critere_de_travail', 'nombre_jour', 'filiere_id', 'description'
    ];
    public $manyToOne = [
        'Formateur' => [
            'model' => "Modules\\PkgFormation\\Models\\Formateur",
            'relation' => 'formateurs' , 
            "foreign_key" => "formateur_id", 
            ],
        'Filiere' => [
            'model' => "Modules\\PkgFormation\\Models\\Filiere",
            'relation' => 'filieres' , 
            "foreign_key" => "filiere_id", 
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
     * Relation BelongsTo pour Filiere.
     *
     * @return BelongsTo
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'filiere_id', 'id');
    }


    /**
     * Relation HasMany pour Projets.
     *
     * @return HasMany
     */
    public function transfertCompetences(): HasMany
    {
        return $this->hasMany(TransfertCompetence::class, 'projet_id', 'id');
    }
    /**
     * Relation HasMany pour Projets.
     *
     * @return HasMany
     */
    public function affectationProjets(): HasMany
    {
        return $this->hasMany(AffectationProjet::class, 'projet_id', 'id');
    }
    /**
     * Relation HasMany pour Projets.
     *
     * @return HasMany
     */
    public function taches(): HasMany
    {
        return $this->hasMany(Tache::class, 'projet_id', 'id');
    }
    /**
     * Relation HasMany pour Projets.
     *
     * @return HasMany
     */
    public function livrables(): HasMany
    {
        return $this->hasMany(Livrable::class, 'projet_id', 'id');
    }
    /**
     * Relation HasMany pour Projets.
     *
     * @return HasMany
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'projet_id', 'id');
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
