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
use Modules\PkgCompetences\Models\Chapitre;
use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Modules\PkgApprentissage\Models\RealisationUa;
use Modules\PkgRealisationTache\Models\RealisationTache;

/**
 * Classe BaseRealisationChapitre
 * Cette classe sert de base pour le modèle RealisationChapitre.
 */
class BaseRealisationChapitre extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'chapitre',
      //  'etatRealisationChapitre',
      //  'realisationUa',
      //  'realisationTache'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "RealisationTache.RealisationProjet.AffectationProjet.Projet.Formateur.user,RealisationUa.RealisationMicroCompetence.Apprenant.user";
        // Colonne dynamique : apprenant
        $sql = "SELECT CONCAT(a.nom, ' ', a.prenom)
        FROM realisation_chapitres rc
        JOIN realisation_uas rua ON rc.realisation_ua_id = rua.id
        JOIN realisation_micro_competences rmc ON rua.realisation_micro_competence_id = rmc.id
        JOIN apprenants a ON rmc.apprenant_id = a.id
        WHERE rc.id = realisation_chapitres.id";
        static::addDynamicAttribute('apprenant', $sql);
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'chapitre_id', 'etat_realisation_chapitre_id', 'date_debut', 'date_fin', 'dernier_update', 'realisation_ua_id', 'realisation_tache_id', 'commentaire_formateur'
    ];
    public $manyToOne = [
        'Chapitre' => [
            'model' => "Modules\\PkgCompetences\\Models\\Chapitre",
            'relation' => 'chapitres' , 
            "foreign_key" => "chapitre_id", 
            ],
        'EtatRealisationChapitre' => [
            'model' => "Modules\\PkgApprentissage\\Models\\EtatRealisationChapitre",
            'relation' => 'etatRealisationChapitres' , 
            "foreign_key" => "etat_realisation_chapitre_id", 
            ],
        'RealisationUa' => [
            'model' => "Modules\\PkgApprentissage\\Models\\RealisationUa",
            'relation' => 'realisationUas' , 
            "foreign_key" => "realisation_ua_id", 
            ],
        'RealisationTache' => [
            'model' => "Modules\\PkgRealisationTache\\Models\\RealisationTache",
            'relation' => 'realisationTaches' , 
            "foreign_key" => "realisation_tache_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Chapitre.
     *
     * @return BelongsTo
     */
    public function chapitre(): BelongsTo
    {
        return $this->belongsTo(Chapitre::class, 'chapitre_id', 'id');
    }
    /**
     * Relation BelongsTo pour EtatRealisationChapitre.
     *
     * @return BelongsTo
     */
    public function etatRealisationChapitre(): BelongsTo
    {
        return $this->belongsTo(EtatRealisationChapitre::class, 'etat_realisation_chapitre_id', 'id');
    }
    /**
     * Relation BelongsTo pour RealisationUa.
     *
     * @return BelongsTo
     */
    public function realisationUa(): BelongsTo
    {
        return $this->belongsTo(RealisationUa::class, 'realisation_ua_id', 'id');
    }
    /**
     * Relation BelongsTo pour RealisationTache.
     *
     * @return BelongsTo
     */
    public function realisationTache(): BelongsTo
    {
        return $this->belongsTo(RealisationTache::class, 'realisation_tache_id', 'id');
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
