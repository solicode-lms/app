<?php
 

namespace Modules\PkgGestionTaches\Models;
use Modules\PkgGestionTaches\Models\Base\BaseRealisationTache;

class RealisationTache extends BaseRealisationTache
{
    public function livrables()
    {
        return  "Livrables";
    }
}
