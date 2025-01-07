<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;

use Modules\Core\Models\SysModule;
use Modules\PkgAutorisation\Models\Permission;

/**
 * Classe BaseSysController
 * Cette classe sert de base pour le modèle SysController.
 */
class BaseSysController extends Model
{
    use HasFactory, HasDynamicContext;

    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'module_id', 'name', 'slug', 'description', 'is_active'
    ];

    /**
     * Relation BelongsTo pour SysModule.
     *
     * @return BelongsTo
     */
    public function sysModule(): BelongsTo
    {
        return $this->belongsTo(SysModule::class, 'module_id', 'id');
    }


    /**
     * Relation HasMany pour Permissions.
     *
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, '_id', 'id');
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
