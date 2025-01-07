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
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Models\SysColor;
use Modules\Core\Models\SysController;
use Modules\Core\Models\SysModel;

/**
 * Classe BaseSysModule
 * Cette classe sert de base pour le modèle SysModule.
 */
class BaseSysModule extends BaseModel
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
        'name', 'slug', 'description', 'is_active', 'order', 'version', 'color_id'
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
     * Relation HasMany pour FeatureDomains.
     *
     * @return HasMany
     */
    public function featureDomains(): HasMany
    {
        return $this->hasMany(FeatureDomain::class, 'sysModule_id', 'id');
    }
    /**
     * Relation HasMany pour SysControllers.
     *
     * @return HasMany
     */
    public function sysControllers(): HasMany
    {
        return $this->hasMany(SysController::class, 'sysModule_id', 'id');
    }
    /**
     * Relation HasMany pour SysModels.
     *
     * @return HasMany
     */
    public function sysModels(): HasMany
    {
        return $this->hasMany(SysModel::class, 'sysModule_id', 'id');
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
