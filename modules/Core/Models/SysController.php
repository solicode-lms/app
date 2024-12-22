<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\SysModule;

class SysController extends Model
{
    use HasFactory;

    protected $fillable = ['module_id', 'name', 'slug', 'description', 'is_active'];

    public function sysModule()
    {
        return $this->belongsTo(SysModule::class, 'module_id', 'id');
    }


    public function __toString()
    {
        return $this->id;
    }

}
