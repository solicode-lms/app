<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\Feature;
use Modules\Core\Models\SysModule;

class BaseFeatureDomain extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['name', 'slug', 'description', 'module_id'];

    public function sysModule()
    {
        return $this->belongsTo(SysModule::class, 'module_id', 'id');
    }



    public function features()
    {
        return $this->hasMany(Feature::class, 'featureDomain_id', 'id');
    }

    public function __toString()
    {
        return $this->name;
    }
}
