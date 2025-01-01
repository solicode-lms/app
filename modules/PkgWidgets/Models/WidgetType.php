<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgWidgets\Models\Widget;

class WidgetType extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['type', 'description'];




    public function widgets()
    {
        return $this->hasMany(Widget::class, 'widgetType_id', 'id');
    }

    public function __toString()
    {
        return $this->type;
    }
}
