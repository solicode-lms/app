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
use Modules\PkgAutoformation\Models\Chapitre;
use Modules\PkgAutoformation\Models\RealisationFormation;
use Modules\PkgAutoformation\Models\EtatChapitre;

/**
 * Classe BaseRealisationChapitre
 * Cette classe sert de base pour le modèle RealisationChapitre.
 */
class BaseRealisationChapitre extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'chapitre',
      //  'realisationFormation',
      //  'etatChapitre'
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
        'date_debut', 'date_fin', 'chapitre_id', 'realisation_formation_id', 'etat_chapitre_id'
    ];
    public $manyToOne = [
        'Chapitre' => [
            'model' => "Modules\\PkgAutoformation\\Models\\Chapitre",
            'relation' => 'chapitres' , 
            "foreign_key" => "chapitre_id", 
            ],
        'RealisationFormation' => [
            'model' => "Modules\\PkgAutoformation\\Models\\RealisationFormation",
            'relation' => 'realisationFormations' , 
            "foreign_key" => "realisation_formation_id", 
            ],
        'EtatChapitre' => [
            'model' => "Modules\\PkgAutoformation\\Models\\EtatChapitre",
            'relation' => 'etatChapitres' , 
            "foreign_key" => "etat_chapitre_id", 
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
     * Relation BelongsTo pour RealisationFormation.
     *
     * @return BelongsTo
     */
    public function realisationFormation(): BelongsTo
    {
        return $this->belongsTo(RealisationFormation::class, 'realisation_formation_id', 'id');
    }
    /**
     * Relation BelongsTo pour EtatChapitre.
     *
     * @return BelongsTo
     */
    public function etatChapitre(): BelongsTo
    {
        return $this->belongsTo(EtatChapitre::class, 'etat_chapitre_id', 'id');
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
