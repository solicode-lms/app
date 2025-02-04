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
use Modules\Core\Models\SysModel;
use Modules\Core\Models\SysModule;

/**
 * Classe BaseSysColor
 * Cette classe sert de base pour le modèle SysColor.
 */
class BaseSysColor extends BaseModel
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
        'name', 'hex'
    ];



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
    public function sysModules(): HasMany
    {
        return $this->hasMany(SysModule::class, 'sys_color_id', 'id');
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
