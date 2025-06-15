<?php
 

namespace Modules\PkgGapp\Models;
use Modules\PkgGapp\Models\Base\BaseERelationship;

class ERelationship extends BaseERelationship
{

     protected $with = [
       'sourceEModel',
       'targetEModel'
    ];
}
