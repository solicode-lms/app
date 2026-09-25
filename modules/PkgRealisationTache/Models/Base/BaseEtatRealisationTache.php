<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationTache\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\Core\Models\SysColor;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgRealisationTache\Models\RealisationTache;

/**
 * Classe BaseEtatRealisationTache
 * Cette classe sert de base pour le modèle EtatRealisationTache.
 */
class BaseEtatRealisationTache extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'workflowTache',
      //  'sysColor',
      //  'formateur'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "formateur.user";
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'ordre', 'nom', 'workflow_tache_id', 'sys_color_id', 'is_editable_only_by_formateur', 'reference', 'formateur_id', 'description'
    ];
    public $manyToOne = [
        'workflowTache' => [
            'model' => "Modules\\PkgRealisationTache\\Models\\WorkflowTache",
            'relation' => 'workflowTache' , 
            "foreign_key" => "workflow_tache_id", 
            ],
        'sysColor' => [
            'model' => "Modules\\Core\\Models\\SysColor",
            'relation' => 'sysColor' , 
            "foreign_key" => "sys_color_id", 
            ],
        'formateur' => [
            'model' => "Modules\\PkgFormation\\Models\\Formateur",
            'relation' => 'formateur' , 
            "foreign_key" => "formateur_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour WorkflowTache.
     *
     * @return BelongsTo
     */
    public function workflowTache(): BelongsTo
    {
        return $this->belongsTo(WorkflowTache::class, 'workflow_tache_id', 'id');
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
     * Relation BelongsTo pour Formateur.
     *
     * @return BelongsTo
     */
    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class, 'formateur_id', 'id');
    }


    /**
     * Relation HasMany pour EtatRealisationTaches.
     *
     * @return HasMany
     */
    public function realisationTaches(): HasMany
    {
        return $this->hasMany(RealisationTache::class, 'etat_realisation_tache_id', 'id');
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
