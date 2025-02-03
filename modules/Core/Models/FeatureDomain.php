<?php
// il manque
// public function features()
// {
//     return $this->hasMany(Feature::class, 'feature_domain_id', 'id');
// }

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Base\BaseFeatureDomain;
use Modules\Core\Models\SysModule;

class FeatureDomain extends BaseFeatureDomain
{


}
