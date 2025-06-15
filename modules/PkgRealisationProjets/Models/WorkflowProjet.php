<?php


namespace Modules\PkgRealisationProjets\Models;
use Modules\PkgRealisationProjets\Models\Base\BaseWorkflowProjet;

class WorkflowProjet extends BaseWorkflowProjet
{
    protected $with = [
        'sysColor'
    ];
}
