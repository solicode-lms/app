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
use Modules\PkgCreationProjet\Models\Projet;
use Modules\Core\Models\SysColor;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgCreationTache\Models\Tache;

/**
 * Classe BaseLabelProjet
 * Cette classe sert de base pour le modèle LabelProjet.
 */
class BaseLabelProjet extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'projet',
      //  'sysColor'
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
        'nom', 'description', 'projet_id', 'sys_color_id'
    ];
    public $manyToMany = [
        'RealisationTache' => ['relation' => 'realisationTaches' , "foreign_key" => "realisation_tache_id" ],
        'Tache' => ['relation' => 'taches' , "foreign_key" => "tache_id" ]
    ];
    public $manyToOne = [
        'Projet' => [
            'model' => "Modules\\PkgCreationProjet\\Models\\Projet",
            'relation' => 'projets' , 
            "foreign_key" => "projet_id", 
            ],
        'SysColor' => [
            'model' => "Modules\\Core\\Models\\SysColor",
            'relation' => 'sysColors' , 
            "foreign_key" => "sys_color_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Projet.
     *
     * @return BelongsTo
     */
    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'projet_id', 'id');
    }
    /**
     * Relation BelongsTo pour SysColor.
     *
     * @return BelongsTo
     */
    public function sysColor(): BelongsTo
    {
        return $this->belongsTo(SysColor::class, 'sys_color_id', 'id');
    }

    /**
     * Relation ManyToMany pour RealisationTaches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function realisationTaches()
    {
        return $this->belongsToMany(RealisationTache::class, 'label_realisation_tache');
    }
    /**
     * Relation ManyToMany pour Taches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taches()
    {
        return $this->belongsToMany(Tache::class, 'label_tache');
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
