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
use Modules\PkgGestionTaches\Models\RealisationTache;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgApprenants\Models\Apprenant;

/**
 * Classe BaseCommentaireRealisationTache
 * Cette classe sert de base pour le modèle CommentaireRealisationTache.
 */
class BaseCommentaireRealisationTache extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
        'realisationTache',
        'formateur',
        'apprenant'
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
        'commentaire', 'dateCommentaire', 'realisation_tache_id', 'formateur_id', 'apprenant_id'
    ];
    public $manyToOne = [
        'RealisationTache' => [
            'model' => "Modules\\PkgGestionTaches\\Models\\RealisationTache",
            'relation' => 'realisationTaches' , 
            "foreign_key" => "realisation_tache_id", 
            ],
        'Formateur' => [
            'model' => "Modules\\PkgFormation\\Models\\Formateur",
            'relation' => 'formateurs' , 
            "foreign_key" => "formateur_id", 
            ],
        'Apprenant' => [
            'model' => "Modules\\PkgApprenants\\Models\\Apprenant",
            'relation' => 'apprenants' , 
            "foreign_key" => "apprenant_id", 
            ]
    ];


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
     * Relation BelongsTo pour Formateur.
     *
     * @return BelongsTo
     */
    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class, 'formateur_id', 'id');
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
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->id ?? "";
    }
}
