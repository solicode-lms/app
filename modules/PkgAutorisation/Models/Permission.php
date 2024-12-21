<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgAutorisation\Models\Role;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'guard_name'];


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }

    public function __toString()
    {
        return $this->id;
    }

}
