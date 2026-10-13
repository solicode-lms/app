<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgTableauBord\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgFormation\Models\Formateur;

/**
 * Classe BaseRemediation
 * Cette classe sert de base pour le modèle Remediation.
 */
class BaseRemediation extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'realisationTache',
      //  'formateur'
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
        'realisation_tache_id', 'formateur_id', 'type_remediation', 'diagnostic', 'est_resolu', 'reference'
    ];
    public $manyToOne = [
        'realisationTache' => [
            'model' => "Modules\\PkgRealisationTache\\Models\\RealisationTache",
            'relation' => 'realisationTache' , 
            "foreign_key" => "realisation_tache_id", 
            ],
        'formateur' => [
            'model' => "Modules\\PkgFormation\\Models\\Formateur",
            'relation' => 'formateur' , 
            "foreign_key" => "formateur_id", 
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
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->id ?? "";
    }
}
