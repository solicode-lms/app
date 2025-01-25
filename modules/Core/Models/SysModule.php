<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Base\BaseSysModel;

class SysModule extends BaseSysModel
{
  
       /**
     * Relation avec les domaines de fonctionnalités.
     * Un module peut avoir plusieurs domaines de fonctionnalités.
     */
    public function featureDomains()
    {
        return $this->hasMany(FeatureDomain::class, 'module_id', 'id');
    }


}
