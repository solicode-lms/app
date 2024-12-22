<?php
// il manque
// public function features()
// {
//     return $this->hasMany(Feature::class, 'domain_id', 'id');
// }

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

    public function features()
    {
        return $this->hasMany(Feature::class, 'domain_id', 'id');
    }

    public function __toString()
    {
        return $this->id;
    }

}
