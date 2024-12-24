<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\SysModule;
use Modules\default\Models\SysColor;

class SysModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'model', 'description', 'module_id'];

    public function sysModule()
    {
        return $this->belongsTo(SysModule::class, 'module_id', 'id');
    }

    public function sysColors()
    {
        return $this->belongsToMany(SysColor::class, 'model_color');
    }

    public function __toString()
    {
        return $this->name;
    }

}
