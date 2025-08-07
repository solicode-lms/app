<?php
 

namespace Modules\PkgRealisationTache\Models;
use Modules\PkgRealisationTache\Models\Base\BaseEtatRealisationTache;

class EtatRealisationTache extends BaseEtatRealisationTache
{
      protected $with = [
       'workflowTache',
       'sysColor',
    ];

   

}
