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
use Modules\PkgApprenants\Models\Nationalite;
use Modules\PkgApprenants\Models\NiveauxScolaire;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgGestionTaches\Models\CommentaireRealisationTache;
use Modules\PkgRealisationProjets\Models\RealisationProjet;

/**
 * Classe BaseApprenant
 * Cette classe sert de base pour le modèle Apprenant.
 */
class BaseApprenant extends BaseModel
{
    use HasFactory, HasDynamicContext;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  false;
        // Colonne dynamique : nom_filiere
        $sql = "SELECT f.nom AS filiere_nom
        FROM apprenants a
        JOIN apprenant_groupe ag ON a.id = ag.apprenant_id
        JOIN groupes g ON ag.groupe_id = g.id
        JOIN filieres f ON g.filiere_id = f.id
        WHERE a.id = apprenants.id";
        static::addDynamicAttribute('nom_filiere', $sql);
        // Colonne dynamique : nombre_realisation_taches_en_cours
        $sql = "SELECT count(*) FROM realisation_taches rt 
        JOIN realisation_projets rp ON rt.realisation_projet_id = rp.id 
        JOIN etat_realisation_taches ert ON rt.etat_realisation_tache_id = ert.id 
        WHERE rp.apprenant_id = apprenants.id AND ert.nom = 'En cours'";
        static::addDynamicAttribute('nombre_realisation_taches_en_cours', $sql);
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'nom_arab', 'prenom', 'prenom_arab', 'profile_image', 'cin', 'date_naissance', 'sexe', 'nationalite_id', 'lieu_naissance', 'diplome', 'adresse', 'niveaux_scolaire_id', 'tele_num', 'user_id', 'matricule', 'date_inscription', 'actif'
    ];
    public $manyToMany = [
        'Groupe' => ['relation' => 'groupes' , "foreign_key" => "groupe_id" ]
    ];


    /**
     * Relation BelongsTo pour Nationalite.
     *
     * @return BelongsTo
     */
    public function nationalite(): BelongsTo
    {
        return $this->belongsTo(Nationalite::class, 'nationalite_id', 'id');
    }
    /**
     * Relation BelongsTo pour NiveauxScolaire.
     *
     * @return BelongsTo
     */
    public function niveauxScolaire(): BelongsTo
    {
        return $this->belongsTo(NiveauxScolaire::class, 'niveaux_scolaire_id', 'id');
    }
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
     * Relation ManyToMany pour Groupes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'apprenant_groupe');
    }

    /**
     * Relation HasMany pour Apprenants.
     *
     * @return HasMany
     */
    public function commentaireRealisationTaches(): HasMany
    {
        return $this->hasMany(CommentaireRealisationTache::class, 'apprenant_id', 'id');
    }
    /**
     * Relation HasMany pour Apprenants.
     *
     * @return HasMany
     */
    public function realisationProjets(): HasMany
    {
        return $this->hasMany(RealisationProjet::class, 'apprenant_id', 'id');
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
