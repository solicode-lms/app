<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\User;

class BaseRole extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['name', 'guard_name'];


    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'model_has_roles');
    }



    public function __toString()
    {
        return $this->name;
    }
}
