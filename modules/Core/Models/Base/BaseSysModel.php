<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\SysColor;
use Modules\Core\Models\SysModule;
use Modules\PkgWidgets\Models\Widget;

class BaseSysModel extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['name', 'model', 'description', 'module_id', 'color_id'];

    public function sysColor()
    {
        return $this->belongsTo(SysColor::class, 'color_id', 'id');
    }
    public function sysModule()
    {
        return $this->belongsTo(SysModule::class, 'module_id', 'id');
    }



    public function widgets()
    {
        return $this->hasMany(Widget::class, 'sysModel_id', 'id');
    }

    public function __toString()
    {
        return $this->name;
    }
}
