<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgAutoformation\Models\Formation;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgAutoformation\Models\EtatFormation;
use Modules\PkgAutoformation\Models\RealisationChapitre;

/**
 * Classe BaseRealisationFormation
 * Cette classe sert de base pour le modèle RealisationFormation.
 */
class BaseRealisationFormation extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'formation',
      //  'apprenant',
      //  'etatFormation'
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
        'date_debut', 'date_fin', 'formation_id', 'apprenant_id', 'etat_formation_id'
    ];
    public $manyToOne = [
        'Formation' => [
            'model' => "Modules\\PkgAutoformation\\Models\\Formation",
            'relation' => 'formations' , 
            "foreign_key" => "formation_id", 
            ],
        'Apprenant' => [
            'model' => "Modules\\PkgApprenants\\Models\\Apprenant",
            'relation' => 'apprenants' , 
            "foreign_key" => "apprenant_id", 
            ],
        'EtatFormation' => [
            'model' => "Modules\\PkgAutoformation\\Models\\EtatFormation",
            'relation' => 'etatFormations' , 
            "foreign_key" => "etat_formation_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Formation.
     *
     * @return BelongsTo
     */
    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id', 'id');
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
     * Relation BelongsTo pour EtatFormation.
     *
     * @return BelongsTo
     */
    public function etatFormation(): BelongsTo
    {
        return $this->belongsTo(EtatFormation::class, 'etat_formation_id', 'id');
    }


    /**
     * Relation HasMany pour RealisationFormations.
     *
     * @return HasMany
     */
    public function realisationChapitres(): HasMany
    {
        return $this->hasMany(RealisationChapitre::class, 'realisation_formation_id', 'id');
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
