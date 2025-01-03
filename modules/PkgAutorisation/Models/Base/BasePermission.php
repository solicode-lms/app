<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\Feature;
use Modules\Core\Models\SysController;
use Modules\PkgAutorisation\Models\Role;

class BasePermission extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['name', 'guard_name', 'controller_id'];

    public function sysController()
    {
        return $this->belongsTo(SysController::class, 'controller_id', 'id');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_permission');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }



    public function __toString()
    {
        return $this->name;
    }
}
