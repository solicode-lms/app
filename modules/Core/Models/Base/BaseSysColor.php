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
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\Core\Models\SysModel;
use Modules\PkgEvaluateurs\Models\EtatEvaluationProjet;
use Modules\PkgRealisationTache\Models\LabelRealisationTache;
use Modules\Core\Models\SysModule;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\PkgWidgets\Models\SectionWidget;
use Modules\PkgWidgets\Models\Widget;
use Modules\PkgRealisationTache\Models\WorkflowTache;

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
    public function etatEvaluationProjets(): HasMany
    {
        return $this->hasMany(EtatEvaluationProjet::class, 'sys_color_id', 'id');
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
    public function etatsRealisationProjets(): HasMany
    {
        return $this->hasMany(EtatsRealisationProjet::class, 'sys_color_id', 'id');
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
    public function workflowTaches(): HasMany
    {
        return $this->hasMany(WorkflowTache::class, 'sys_color_id', 'id');
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
