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
use Modules\Core\Models\Feature;
use Modules\Core\Models\SysController;
use Modules\PkgAutorisation\Models\Role;

/**
 * Classe BasePermission
 * Cette classe sert de base pour le modèle Permission.
 */
class BasePermission extends BaseModel
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
        'name', 'guard_name', 'controller_id'
    ];

    /**
     * Relation BelongsTo pour SysController.
     *
     * @return BelongsTo
     */
    public function sysController(): BelongsTo
    {
        return $this->belongsTo(SysController::class, 'controller_id', 'id');
    }

    /**
     * Relation ManyToMany pour Features.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_permission');
    }
    /**
     * Relation ManyToMany pour Roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
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
