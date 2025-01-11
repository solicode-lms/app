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
use Modules\Core\Models\SysColor;
use Modules\Core\Models\SysModule;
use Modules\PkgWidgets\Models\Widget;

/**
 * Classe BaseSysModel
 * Cette classe sert de base pour le modèle SysModel.
 */
class BaseSysModel extends BaseModel
{
    use HasFactory, HasDynamicContext;

    public function __construct() {
        parent::__construct(); 
        $this->isOwnedByUser =  false;
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'color_id', 'description', 'model', 'module_id', 'name'
    ];

    /**
     * Relation BelongsTo pour SysColor.
     *
     * @return BelongsTo
     */
    public function sysColor(): BelongsTo
    {
        return $this->belongsTo(SysColor::class, 'color_id', 'id');
    }
    /**
     * Relation BelongsTo pour SysModule.
     *
     * @return BelongsTo
     */
    public function sysModule(): BelongsTo
    {
        return $this->belongsTo(SysModule::class, 'module_id', 'id');
    }


    /**
     * Relation HasMany pour Widgets.
     *
     * @return HasMany
     */
    public function widgets(): HasMany
    {
        return $this->hasMany(Widget::class, 'sys_model_id', 'id');
    }

    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
