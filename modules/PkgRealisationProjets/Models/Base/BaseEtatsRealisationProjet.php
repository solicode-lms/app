<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgFormation\Models\Formateur;
use Modules\Core\Models\SysColor;
use Modules\PkgRealisationProjets\Models\WorkflowProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;

/**
 * Classe BaseEtatsRealisationProjet
 * Cette classe sert de base pour le modèle EtatsRealisationProjet.
 */
class BaseEtatsRealisationProjet extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

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
        'formateur_id', 'titre', 'description', 'sys_color_id', 'workflow_projet_id', 'is_editable_by_formateur'
    ];
    public $manyToOne = [
        'Formateur' => [
            'model' => "Modules\\PkgFormation\\Models\\Formateur",
            'relation' => 'formateurs' , 
            "foreign_key" => "formateur_id", 
            ],
        'SysColor' => [
            'model' => "Modules\\Core\\Models\\SysColor",
            'relation' => 'sysColors' , 
            "foreign_key" => "sys_color_id", 
            ],
        'WorkflowProjet' => [
            'model' => "Modules\\PkgRealisationProjets\\Models\\WorkflowProjet",
            'relation' => 'workflowProjets' , 
            "foreign_key" => "workflow_projet_id", 
            ]
    ];


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
     * Relation BelongsTo pour SysColor.
     *
     * @return BelongsTo
     */
    public function sysColor(): BelongsTo
    {
        return $this->belongsTo(SysColor::class, 'sys_color_id', 'id');
    }
    /**
     * Relation BelongsTo pour WorkflowProjet.
     *
     * @return BelongsTo
     */
    public function workflowProjet(): BelongsTo
    {
        return $this->belongsTo(WorkflowProjet::class, 'workflow_projet_id', 'id');
    }


    /**
     * Relation HasMany pour EtatsRealisationProjets.
     *
     * @return HasMany
     */
    public function realisationProjets(): HasMany
    {
        return $this->hasMany(RealisationProjet::class, 'etats_realisation_projet_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->titre ?? "";
    }
}
