<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public bool $isOwnedByUser = false;
}
