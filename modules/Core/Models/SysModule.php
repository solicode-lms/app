<?php
// TODO : il manque 
// public function featureDomains()
// {
//     return $this->hasMany(FeatureDomain::class, 'module_id', 'id');
// }


namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysModule extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'is_active', 'order', 'version'];


    public function __toString()
    {
        return $this->id;
    }

       /**
     * Relation avec les domaines de fonctionnalités.
     * Un module peut avoir plusieurs domaines de fonctionnalités.
     */
    public function featureDomains()
    {
        return $this->hasMany(FeatureDomain::class, 'module_id', 'id');
    }

}
