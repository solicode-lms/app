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
use Modules\Core\Models\SysColor;
use Modules\PkgWidgets\Models\Widget;

/**
 * Classe BaseSectionWidget
 * Cette classe sert de base pour le modèle SectionWidget.
 */
class BaseSectionWidget extends BaseModel
{
    use HasFactory, HasDynamicContext;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  false;
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'titre', 'sous_titre', 'icone', 'ordre', 'sys_color_id'
    ];


    /**
     * Relation BelongsTo pour SysColor.
     *
     * @return BelongsTo
     */
    public function sysColor(): BelongsTo
    {
        return $this->belongsTo(SysColor::class, 'sys_color_id', 'id');
    }


    /**
     * Relation HasMany pour SectionWidgets.
     *
     * @return HasMany
     */
    public function widgets(): HasMany
    {
        return $this->hasMany(Widget::class, 'section_widget_id', 'id');
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
