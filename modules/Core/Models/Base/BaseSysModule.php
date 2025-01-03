<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Models\SysColor;
use Modules\Core\Models\SysController;
use Modules\Core\Models\SysModel;

class BaseSysModule extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['name', 'slug', 'description', 'is_active', 'order', 'version', 'color_id'];

    public function sysColor()
    {
        return $this->belongsTo(SysColor::class, 'color_id', 'id');
    }



    public function featureDomains()
    {
        return $this->hasMany(FeatureDomain::class, 'sysModule_id', 'id');
    }
    public function sysControllers()
    {
        return $this->hasMany(SysController::class, 'sysModule_id', 'id');
    }
    public function sysModels()
    {
        return $this->hasMany(SysModel::class, 'sysModule_id', 'id');
    }

    public function __toString()
    {
        return $this->name;
    }
}
