<?php
 

namespace Modules\PkgRealisationTache\Models;
use Modules\PkgRealisationTache\Models\Base\BaseTache;

class Tache extends BaseTache
{ 

     protected $with = [
       'prioriteTache',
    ];

    public function __toString()
    {
        return ($this->prioriteTache ? ($this->prioriteTache->nom . "-") : "") . ($this->titre ?? "");
    }

}
