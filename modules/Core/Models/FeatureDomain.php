<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\SysModule;

class FeatureDomain extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'module_id'];

    public function sysModule()
    {
        return $this->belongsTo(SysModule::class, 'module_id', 'id');
    }


    public function __toString()
    {
        return $this->id;
    }

}
