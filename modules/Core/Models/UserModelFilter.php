<?php
 

namespace Modules\Core\Models;
use Modules\Core\Models\Base\BaseUserModelFilter;

class UserModelFilter extends BaseUserModelFilter
{
    protected $casts = [
        'filters' => 'array',
    ];
}
