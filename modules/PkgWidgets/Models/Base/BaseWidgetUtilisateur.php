<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgWidgets\Models\Widget;

/**
 * Classe BaseWidgetUtilisateur
 * Cette classe sert de base pour le modèle WidgetUtilisateur.
 */
class BaseWidgetUtilisateur extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "user,user";
        // Colonne dynamique : sys_module_id
        $sql = "SELECT sm.id
        FROM widget_utilisateurs wu
        JOIN widgets w ON wu.widget_id = w.id
        JOIN sys_models m ON w.model_id = m.id
        JOIN sys_modules sm ON m.sys_module_id = sm.id
        WHERE wu.id = widget_utilisateurs.id";
        static::addDynamicAttribute('sys_module_id', $sql);
        // Colonne dynamique : package
        $sql = "SELECT sm.name
        FROM widget_utilisateurs wu
        JOIN widgets w ON wu.widget_id = w.id
        JOIN sys_models m ON w.model_id = m.id
        JOIN sys_modules sm ON m.sys_module_id = sm.id
        WHERE wu.id = widget_utilisateurs.id";
        static::addDynamicAttribute('package', $sql);
        // Colonne dynamique : type
        $sql = "SELECT wt.type
        FROM widgets w
        JOIN widget_types wt ON w.type_id = wt.id
        WHERE w.id = widget_utilisateurs.widget_id";
        static::addDynamicAttribute('type', $sql);
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'widget_id', 'ordre', 'titre', 'sous_titre', 'visible'
    ];


    /**
     * Relation BelongsTo pour User.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    /**
     * Relation BelongsTo pour Widget.
     *
     * @return BelongsTo
     */
    public function widget(): BelongsTo
    {
        return $this->belongsTo(Widget::class, 'widget_id', 'id');
    }





    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->titre ?? "";
    }
}
