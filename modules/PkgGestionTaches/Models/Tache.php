<?php
 

namespace Modules\PkgGestionTaches\Models;
use Modules\PkgGestionTaches\Models\Base\BaseTache;

class Tache extends BaseTache
{ 

    //  protected $with = [
    //    'prioriteTache',
    //    'projet'
    // ];

    public function __toString()
    {
        return ($this->prioriteTache ? ($this->prioriteTache->nom . "-") : "") . ($this->titre ?? "");
    }

}
