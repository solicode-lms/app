<?php
 

namespace Modules\PkgAutoformation\Models;
use Modules\PkgAutoformation\Models\Base\BaseEtatFormation;

class EtatFormation extends BaseEtatFormation
{
    protected $with = [
       'workflowFormation',
       'sysColor',
       'formateur'
    ];

}
