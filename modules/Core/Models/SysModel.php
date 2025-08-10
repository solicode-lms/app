<?php
 

namespace Modules\Core\Models;
use Modules\Core\Models\Base\BaseSysModel;

class SysModel extends BaseSysModel
{

     protected $with = [
       'sysModule',
       'sysColor'
    ];

    public function generateReference(): string
    {
        return $this->name ;
    }
}
