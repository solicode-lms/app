<?php
 

namespace Modules\PkgGestionTaches\Models;
use Modules\PkgGestionTaches\Models\Base\BaseEtatRealisationTache;

class EtatRealisationTache extends BaseEtatRealisationTache
{
      protected $with = [
       'workflowTache',
       'sysColor',
    ];


}
