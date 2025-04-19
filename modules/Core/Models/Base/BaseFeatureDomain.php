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
use Modules\Core\Models\SysModule;
use Modules\Core\Models\Feature;

/**
 * Classe BaseFeatureDomain
 * Cette classe sert de base pour le modèle FeatureDomain.
 */
class BaseFeatureDomain extends BaseModel
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
        'name', 'slug', 'description', 'sys_module_id'
    ];
    public $manyToOne = [
        'SysModule' => [
            'model' => "Modules\\Core\\Models\\SysModule",
            'relation' => 'sysModules' , 
            "foreign_key" => "sys_module_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour SysModule.
     *
     * @return BelongsTo
     */
    public function sysModule(): BelongsTo
    {
        return $this->belongsTo(SysModule::class, 'sys_module_id', 'id');
    }


    /**
     * Relation HasMany pour FeatureDomains.
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
        return $this->name ?? "";
    }
}
