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
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgApprentissage\Models\RealisationUa;

/**
 * Classe BaseRealisationUaProjet
 * Cette classe sert de base pour le modèle RealisationUaProjet.
 */
class BaseRealisationUaProjet extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'realisationTache',
      //  'realisationUa'
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
        'realisation_tache_id', 'realisation_ua_id', 'note', 'bareme', 'remarque_formateur', 'date_debut', 'date_fin'
    ];
    public $manyToOne = [
        'RealisationTache' => [
            'model' => "Modules\\PkgRealisationTache\\Models\\RealisationTache",
            'relation' => 'realisationTaches' , 
            "foreign_key" => "realisation_tache_id", 
            ],
        'RealisationUa' => [
            'model' => "Modules\\PkgApprentissage\\Models\\RealisationUa",
            'relation' => 'realisationUas' , 
            "foreign_key" => "realisation_ua_id", 
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
     * Relation BelongsTo pour RealisationUa.
     *
     * @return BelongsTo
     */
    public function realisationUa(): BelongsTo
    {
        return $this->belongsTo(RealisationUa::class, 'realisation_ua_id', 'id');
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
