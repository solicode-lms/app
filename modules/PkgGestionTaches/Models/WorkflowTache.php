<?php
 

namespace Modules\PkgRealisationTache\Models;
use Modules\PkgRealisationTache\Models\Base\BaseWorkflowTache;

class WorkflowTache extends BaseWorkflowTache
{ 
    protected $with = [
       'sysColor'
    ];

}
