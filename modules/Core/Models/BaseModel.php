<?php

namespace Modules\Core\Models;

use App\Traits\BaseModelTrait;
use App\Traits\HasReference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Core\App\Traits\HasDynamicAttributes;

class BaseModel extends Model
{
    use HasReference, BaseModelTrait, HasDynamicAttributes;
    
}
