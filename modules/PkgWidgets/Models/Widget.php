<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\SysModel;
use Modules\PkgWidgets\Models\WidgetOperation;
use Modules\PkgWidgets\Models\WidgetType;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type_id', 'model_id', 'operation_id', 'color', 'icon', 'label', 'parameters'];

    public function sysModel()
    {
        return $this->belongsTo(SysModel::class, 'model_id', 'id');
    }
    public function widgetOperation()
    {
        return $this->belongsTo(WidgetOperation::class, 'operation_id', 'id');
    }
    public function widgetType()
    {
        return $this->belongsTo(WidgetType::class, 'type_id', 'id');
    }


    public function __toString()
    {
        return $this->name;
    }

}
