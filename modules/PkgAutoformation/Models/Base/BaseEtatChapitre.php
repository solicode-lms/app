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
use Modules\PkgAutoformation\Models\WorkflowChapitre;
use Modules\Core\Models\SysColor;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgAutoformation\Models\RealisationChapitre;

/**
 * Classe BaseEtatChapitre
 * Cette classe sert de base pour le modèle EtatChapitre.
 */
class BaseEtatChapitre extends BaseModel
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
        'nom', 'workflow_chapitre_id', 'sys_color_id', 'is_editable_only_by_formateur', 'description', 'formateur_id'
    ];


    /**
     * Relation BelongsTo pour WorkflowChapitre.
     *
     * @return BelongsTo
     */
    public function workflowChapitre(): BelongsTo
    {
        return $this->belongsTo(WorkflowChapitre::class, 'workflow_chapitre_id', 'id');
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
     * Relation HasMany pour EtatChapitres.
     *
     * @return HasMany
     */
    public function realisationChapitres(): HasMany
    {
        return $this->hasMany(RealisationChapitre::class, 'etat_chapitre_id', 'id');
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
