<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\SysModule;
use Modules\PkgAutorisation\Models\Permission;

class BaseSysController extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['module_id', 'name', 'slug', 'description', 'is_active'];

    public function sysModule()
    {
        return $this->belongsTo(SysModule::class, 'module_id', 'id');
    }



    public function permissions()
    {
        return $this->hasMany(Permission::class, 'sysController_id', 'id');
    }

    public function __toString()
    {
        return $this->name;
    }
}
