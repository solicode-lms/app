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
use Modules\Core\Models\SysModel;
use Modules\PkgWidgets\Models\WidgetType;
use Modules\PkgWidgets\Models\WidgetOperation;

/**
 * Classe BaseWidget
 * Cette classe sert de base pour le modèle Widget.
 */
class BaseWidget extends BaseModel
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
        'name', 'label', 'model_id', 'type_id', 'operation_id', 'color', 'icon', 'parameters'
    ];

    /**
     * Relation BelongsTo pour SysModel.
     *
     * @return BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(SysModel::class, 'model_id', 'id');
    }
    /**
     * Relation BelongsTo pour WidgetType.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(WidgetType::class, 'type_id', 'id');
    }
    /**
     * Relation BelongsTo pour WidgetOperation.
     *
     * @return BelongsTo
     */
    public function operation(): BelongsTo
    {
        return $this->belongsTo(WidgetOperation::class, 'operation_id', 'id');
    }





    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name ?? "";
    }
}
