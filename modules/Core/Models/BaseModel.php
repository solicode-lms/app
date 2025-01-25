<?php

namespace Modules\Core\Models;

use App\Traits\HasReference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    use HasReference;
    
    public bool $isOwnedByUser = false;

}
