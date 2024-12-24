<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\SysColor;
use Modules\Core\Models\SysModule;

class SysModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'model', 'description', 'module_id', 'color_id'];

    public function sysColor()
    {
        return $this->belongsTo(SysColor::class, 'color_id', 'id');
    }
    public function sysModule()
    {
        return $this->belongsTo(SysModule::class, 'module_id', 'id');
    }


    public function __toString()
    {
        return $this->name;
    }

}
