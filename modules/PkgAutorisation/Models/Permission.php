<?php
// Permission extends ModelsPermission


namespace Modules\PkgAutorisation\Models;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    // Relation avec les permissions enfants
    public function children()
    {
        return $this->belongsToMany(Permission::class, 'permission_hierarchy', 'parent_id', 'child_id');
    }

    // Relation avec les permissions parents
    public function parents()
    {
        return $this->belongsToMany(Permission::class, 'permission_hierarchy', 'child_id', 'parent_id');
    }
}
