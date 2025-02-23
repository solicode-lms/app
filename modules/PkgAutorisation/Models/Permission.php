<?php
// Permission extends ModelsPermission


namespace Modules\PkgAutorisation\Models;

use App\Traits\HasDynamicContext;
use App\Traits\HasReference;
use Modules\Core\Models\Feature;
use Modules\Core\Models\SysController;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{

    use HasReference,HasDynamicContext;
    
    public function sysController()
    {
        return $this->belongsTo(SysController::class, 'controller_id', 'id');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_permission');
    }

    public function __toString()
    {
        return $this->name;
    }
}
