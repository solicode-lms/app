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
use Modules\Core\Models\Feature;
use Modules\Core\Models\SysModule;

/**
 * Classe BaseFeatureDomain
 * Cette classe sert de base pour le modèle FeatureDomain.
 */
class BaseFeatureDomain extends BaseModel
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
        'name', 'slug', 'description', 'module_id'
    ];

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
     * Relation HasMany pour Features.
     *
     * @return HasMany
     */
    public function features(): HasMany
    {
        return $this->hasMany(Feature::class, 'feature_domain_id', 'id');
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
