<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgAutorisation\Models\ModelHasRole;

/**
 * Classe BaseRole
 * Cette classe sert de base pour le modèle Role.
 */
class BaseRole extends BaseModel
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
        'name', 'guard_name'
    ];


    /**
     * Relation ManyToMany pour Permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }
    /**
     * Relation ManyToMany pour Users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'model_has_roles');
    }

    /**
     * Relation HasMany pour Roles.
     *
     * @return HasMany
     */
    public function modelHasRoles(): HasMany
    {
        return $this->hasMany(ModelHasRole::class, 'role_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
