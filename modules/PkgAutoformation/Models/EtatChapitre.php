<?php
 

namespace Modules\PkgAutoformation\Models;
use Modules\PkgAutoformation\Models\Base\BaseEtatChapitre;

class EtatChapitre extends BaseEtatChapitre
{
    protected $with = [
       'workflowChapitre',
       'sysColor',
       'formateur'
    ];

}
