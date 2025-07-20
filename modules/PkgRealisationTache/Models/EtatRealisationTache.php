<?php
 

namespace Modules\PkgRealisationTache\Models;
use Modules\PkgRealisationTache\Models\Base\BaseEtatRealisationTache;

class EtatRealisationTache extends BaseEtatRealisationTache
{
      protected $with = [
       'workflowTache',
       'sysColor',
    ];

    public function generateReference(): string
    {
        return $this->formateur->reference . "-" . $this->workflowTache->reference ;
    }

}
