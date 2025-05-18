<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgGestionTaches\Models\Tache;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgGestionTaches\Models\EtatRealisationTache;
use Modules\PkgValidationProjets\Models\EvaluationRealisationTache;
use Modules\PkgGestionTaches\Models\HistoriqueRealisationTache;
use Modules\PkgGestionTaches\Models\CommentaireRealisationTache;

/**
 * Classe BaseRealisationTache
 * Cette classe sert de base pour le modèle RealisationTache.
 */
class BaseRealisationTache extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "RealisationProjet.AffectationProjet.Projet.Formateur.user,RealisationProjet.Apprenant.user,RealisationProjet.AffectationProjet.evaluateurs.user";
        // Colonne dynamique : projet_title
        $sql = "SELECT p.titre
        FROM realisation_projets rp
        JOIN affectation_projets ap ON ap.id = rp.affectation_projet_id
        JOIN projets p ON p.id = ap.projet_id
        WHERE rp.id = realisation_taches.realisation_projet_id";
        static::addDynamicAttribute('projet_title', $sql);
        // Colonne dynamique : nom_prenom_apprenant
        $sql = "SELECT CONCAT(a.nom, ' ', a.prenom)
        FROM realisation_projets rp
        JOIN apprenants a ON a.id = rp.apprenant_id
        WHERE rp.id = realisation_taches.realisation_projet_id";
        static::addDynamicAttribute('nom_prenom_apprenant', $sql);
        // Colonne dynamique : deadline
        $sql = "SELECT t.dateFin
        FROM taches t
        WHERE t.id = realisation_taches.tache_id";
        static::addDynamicAttribute('deadline', $sql);
        // Colonne dynamique : nombre_livrables
        $sql = "SELECT COUNT(*) 
        FROM livrables_realisations lr
        JOIN livrables l ON l.id = lr.livrable_id
        JOIN livrable_tache lt ON lt.livrable_id = l.id
        WHERE lt.tache_id = realisation_taches.tache_id
        AND lr.realisation_projet_id = realisation_taches.realisation_projet_id";
        static::addDynamicAttribute('nombre_livrables', $sql);
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'tache_id', 'realisation_projet_id', 'dateDebut', 'dateFin', 'etat_realisation_tache_id', 'note', 'remarques_formateur', 'remarques_apprenant'
    ];
    public $manyToOne = [
        'Tache' => [
            'model' => "Modules\\PkgGestionTaches\\Models\\Tache",
            'relation' => 'taches' , 
            "foreign_key" => "tache_id", 
            "sortByPath" => "prioriteTache.ordre"
            ],
        'RealisationProjet' => [
            'model' => "Modules\\PkgRealisationProjets\\Models\\RealisationProjet",
            'relation' => 'realisationProjets' , 
            "foreign_key" => "realisation_projet_id", 
            ],
        'EtatRealisationTache' => [
            'model' => "Modules\\PkgGestionTaches\\Models\\EtatRealisationTache",
            'relation' => 'etatRealisationTaches' , 
            "foreign_key" => "etat_realisation_tache_id", 
            "sortByPath" => "etatRealisationTache.workflowTache.ordre"
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
     * Relation BelongsTo pour RealisationProjet.
     *
     * @return BelongsTo
     */
    public function realisationProjet(): BelongsTo
    {
        return $this->belongsTo(RealisationProjet::class, 'realisation_projet_id', 'id');
    }
    /**
     * Relation BelongsTo pour EtatRealisationTache.
     *
     * @return BelongsTo
     */
    public function etatRealisationTache(): BelongsTo
    {
        return $this->belongsTo(EtatRealisationTache::class, 'etat_realisation_tache_id', 'id');
    }


    /**
     * Relation HasMany pour RealisationTaches.
     *
     * @return HasMany
     */
    public function evaluationRealisationTaches(): HasMany
    {
        return $this->hasMany(EvaluationRealisationTache::class, 'realisation_tache_id', 'id');
    }
    /**
     * Relation HasMany pour RealisationTaches.
     *
     * @return HasMany
     */
    public function historiqueRealisationTaches(): HasMany
    {
        return $this->hasMany(HistoriqueRealisationTache::class, 'realisation_tache_id', 'id');
    }
    /**
     * Relation HasMany pour RealisationTaches.
     *
     * @return HasMany
     */
    public function commentaireRealisationTaches(): HasMany
    {
        return $this->hasMany(CommentaireRealisationTache::class, 'realisation_tache_id', 'id');
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
