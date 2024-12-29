<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\SysModel;
use Modules\Core\Models\SysModule;

class SysColor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'hex'];




    public function sysModels()
    {
        return $this->hasMany(SysModel::class, 'sysColor_id', 'id');
    }
    public function sysModules()
    {
        return $this->hasMany(SysModule::class, 'sysColor_id', 'id');
    }

    public function __toString()
    {
        return $this->name;
    }
}
