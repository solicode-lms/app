<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgQcm\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgQcm\Models\AffectationQcmProjet;
use Modules\PkgQcm\Models\Qcm;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgQcm\Models\EtatRealisationQcm;
use Modules\PkgQcm\Models\ReponseQcm;

/**
 * Classe BaseRealisationQcm
 * Cette classe sert de base pour le modèle RealisationQcm.
 */
class BaseRealisationQcm extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'affectationQcmProjet',
      //  'qcm',
      //  'apprenant',
      //  'etatRealisationQcm'
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
        'reference', 'affectation_qcm_projet_id', 'qcm_id', 'apprenant_id', 'etat_realisation_qcm_id', 'date_debut', 'date_fin', 'date_soumission', 'date_validation', 'note_obtenu', 'statut'
    ];
    public $manyToOne = [
        'affectationQcmProjet' => [
            'model' => "Modules\\PkgQcm\\Models\\AffectationQcmProjet",
            'relation' => 'affectationQcmProjet' , 
            "foreign_key" => "affectation_qcm_projet_id", 
            ],
        'qcm' => [
            'model' => "Modules\\PkgQcm\\Models\\Qcm",
            'relation' => 'qcm' , 
            "foreign_key" => "qcm_id", 
            ],
        'apprenant' => [
            'model' => "Modules\\PkgApprenants\\Models\\Apprenant",
            'relation' => 'apprenant' , 
            "foreign_key" => "apprenant_id", 
            ],
        'etatRealisationQcm' => [
            'model' => "Modules\\PkgQcm\\Models\\EtatRealisationQcm",
            'relation' => 'etatRealisationQcm' , 
            "foreign_key" => "etat_realisation_qcm_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour AffectationQcmProjet.
     *
     * @return BelongsTo
     */
    public function affectationQcmProjet(): BelongsTo
    {
        return $this->belongsTo(AffectationQcmProjet::class, 'affectation_qcm_projet_id', 'id');
    }
    /**
     * Relation BelongsTo pour Qcm.
     *
     * @return BelongsTo
     */
    public function qcm(): BelongsTo
    {
        return $this->belongsTo(Qcm::class, 'qcm_id', 'id');
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
     * Relation BelongsTo pour EtatRealisationQcm.
     *
     * @return BelongsTo
     */
    public function etatRealisationQcm(): BelongsTo
    {
        return $this->belongsTo(EtatRealisationQcm::class, 'etat_realisation_qcm_id', 'id');
    }


    /**
     * Relation HasMany pour RealisationQcms.
     *
     * @return HasMany
     */
    public function reponseQcms(): HasMany
    {
        return $this->hasMany(ReponseQcm::class, 'realisation_qcm_id', 'id');
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
