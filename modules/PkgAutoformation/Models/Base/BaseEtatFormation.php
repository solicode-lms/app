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
use Modules\PkgAutoformation\Models\WorkflowFormation;
use Modules\Core\Models\SysColor;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgAutoformation\Models\RealisationFormation;

/**
 * Classe BaseEtatFormation
 * Cette classe sert de base pour le modèle EtatFormation.
 */
class BaseEtatFormation extends BaseModel
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
        'nom', 'workflow_formation_id', 'sys_color_id', 'is_editable_only_by_formateur', 'formateur_id', 'description'
    ];
    public $manyToOne = [
        'WorkflowFormation' => [
            'model' => "Modules\\PkgAutoformation\\Models\\WorkflowFormation",
            'relation' => 'workflowFormations' , 
            "foreign_key" => "workflow_formation_id", 
            ],
        'SysColor' => [
            'model' => "Modules\\Core\\Models\\SysColor",
            'relation' => 'sysColors' , 
            "foreign_key" => "sys_color_id", 
            ],
        'Formateur' => [
            'model' => "Modules\\PkgFormation\\Models\\Formateur",
            'relation' => 'formateurs' , 
            "foreign_key" => "formateur_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour WorkflowFormation.
     *
     * @return BelongsTo
     */
    public function workflowFormation(): BelongsTo
    {
        return $this->belongsTo(WorkflowFormation::class, 'workflow_formation_id', 'id');
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
     * Relation HasMany pour EtatFormations.
     *
     * @return HasMany
     */
    public function realisationFormations(): HasMany
    {
        return $this->hasMany(RealisationFormation::class, 'etat_formation_id', 'id');
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
