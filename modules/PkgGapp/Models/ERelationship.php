<?php
 

namespace Modules\PkgGapp\Models;
use Modules\PkgGapp\Models\Base\BaseERelationship;

class ERelationship extends BaseERelationship
{
  protected bool $allowReferenceUpdate = false;

     protected $with = [
       'sourceEModel',
       'targetEModel'
    ];
}
