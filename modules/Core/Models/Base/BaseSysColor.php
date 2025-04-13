<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgAutoformation\Models\EtatChapitre;
use Modules\PkgGestionTaches\Models\EtatRealisationTache;
use Modules\Core\Models\SysModel;
use Modules\PkgAutoformation\Models\EtatFormation;
use Modules\PkgGestionTaches\Models\LabelRealisationTache;
use Modules\Core\Models\SysModule;
use Modules\PkgWidgets\Models\SectionWidget;
use Modules\PkgWidgets\Models\Widget;
use Modules\PkgAutoformation\Models\WorkflowChapitre;
use Modules\PkgAutoformation\Models\WorkflowFormation;
use Modules\PkgRealisationProjets\Models\WorkflowProjet;

/**
 * Classe BaseSysColor
 * Cette classe sert de base pour le modèle SysColor.
 */
class BaseSysColor extends BaseModel
{
    use HasFactory, HasDynamicContext;

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
        'name', 'hex'
    ];




    /**
     * Relation HasMany pour SysColors.
     *
     * @return HasMany
     */
    public function etatChapitres(): HasMany
    {
        return $this->hasMany(EtatChapitre::class, 'sys_color_id', 'id');
    }
    /**
     * Relation HasMany pour SysColors.
     *
     * @return HasMany
     */
    public function etatRealisationTaches(): HasMany
    {
        return $this->hasMany(EtatRealisationTache::class, 'sys_color_id', 'id');
    }
    /**
     * Relation HasMany pour SysColors.
     *
     * @return HasMany
     */
    public function sysModels(): HasMany
    {
        return $this->hasMany(SysModel::class, 'sys_color_id', 'id');
    }
    /**
     * Relation HasMany pour SysColors.
     *
     * @return HasMany
     */
    public function etatFormations(): HasMany
    {
        return $this->hasMany(EtatFormation::class, 'sys_color_id', 'id');
    }
    /**
     * Relation HasMany pour SysColors.
     *
     * @return HasMany
     */
    public function labelRealisationTaches(): HasMany
    {
        return $this->hasMany(LabelRealisationTache::class, 'sys_color_id', 'id');
    }
    /**
     * Relation HasMany pour SysColors.
     *
     * @return HasMany
     */
    public function sysModules(): HasMany
    {
        return $this->hasMany(SysModule::class, 'sys_color_id', 'id');
    }
    /**
     * Relation HasMany pour SysColors.
     *
     * @return HasMany
     */
    public function sectionWidgets(): HasMany
    {
        return $this->hasMany(SectionWidget::class, 'sys_color_id', 'id');
    }
    /**
     * Relation HasMany pour SysColors.
     *
     * @return HasMany
     */
    public function widgets(): HasMany
    {
        return $this->hasMany(Widget::class, 'sys_color_id', 'id');
    }
    /**
     * Relation HasMany pour SysColors.
     *
     * @return HasMany
     */
    public function workflowChapitres(): HasMany
    {
        return $this->hasMany(WorkflowChapitre::class, 'sys_color_id', 'id');
    }
    /**
     * Relation HasMany pour SysColors.
     *
     * @return HasMany
     */
    public function workflowFormations(): HasMany
    {
        return $this->hasMany(WorkflowFormation::class, 'sys_color_id', 'id');
    }
    /**
     * Relation HasMany pour SysColors.
     *
     * @return HasMany
     */
    public function workflowProjets(): HasMany
    {
        return $this->hasMany(WorkflowProjet::class, 'sys_color_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name ?? "";
    }
}
