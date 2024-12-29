<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\FeatureDomain;
use Modules\PkgAutorisation\Models\Permission;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'domain_id'];

    public function featureDomain()
    {
        return $this->belongsTo(FeatureDomain::class, 'domain_id', 'id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'feature_permission');
    }



    public function __toString()
    {
        return $this->name;
    }
}
