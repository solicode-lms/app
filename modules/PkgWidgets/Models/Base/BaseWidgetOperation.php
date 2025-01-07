<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;

use Modules\PkgWidgets\Models\Widget;

/**
 * Classe BaseWidgetOperation
 * Cette classe sert de base pour le modèle WidgetOperation.
 */
class BaseWidgetOperation extends Model
{
    use HasFactory, HasDynamicContext;

    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'operation', 'description'
    ];



    /**
     * Relation HasMany pour Widgets.
     *
     * @return HasMany
     */
    public function widgets(): HasMany
    {
        return $this->hasMany(Widget::class, 'widgetOperation_id', 'id');
    }

    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->operation;
    }
}
