<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgAutorisation\Models\Role;

class BaseUser extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['name', 'email', 'email_verified_at', 'password', 'remember_token'];


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles');
    }



    public function __toString()
    {
        return $this->name;
    }
}
