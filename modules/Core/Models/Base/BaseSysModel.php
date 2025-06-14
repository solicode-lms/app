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
use Modules\Core\Models\SysColor;
use Modules\PkgWidgets\Models\Widget;

/**
 * Classe BaseSysModel
 * Cette classe sert de base pour le modèle SysModel.
 */
class BaseSysModel extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'sysModule',
      //  'sysColor'
    ];


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
        'name', 'model', 'sys_module_id', 'sys_color_id', 'icone', 'description'
    ];
    public $manyToOne = [
        'SysModule' => [
            'model' => "Modules\\Core\\Models\\SysModule",
            'relation' => 'sysModules' , 
            "foreign_key" => "sys_module_id", 
            ],
        'SysColor' => [
            'model' => "Modules\\Core\\Models\\SysColor",
            'relation' => 'sysColors' , 
            "foreign_key" => "sys_color_id", 
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
     * Relation BelongsTo pour SysColor.
     *
     * @return BelongsTo
     */
    public function sysColor(): BelongsTo
    {
        return $this->belongsTo(SysColor::class, 'sys_color_id', 'id');
    }


    /**
     * Relation HasMany pour SysModels.
     *
     * @return HasMany
     */
    public function modelIdWidgets(): HasMany
    {
        return $this->hasMany(Widget::class, 'model_id', 'id');
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
