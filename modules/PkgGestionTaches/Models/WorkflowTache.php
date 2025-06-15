<?php
 

namespace Modules\PkgGestionTaches\Models;
use Modules\PkgGestionTaches\Models\Base\BaseWorkflowTache;

class WorkflowTache extends BaseWorkflowTache
{ 
    protected $with = [
       'sysColor'
    ];

}
