<?php
 

namespace Modules\PkgCreationTache\Models;
use Modules\PkgCreationTache\Models\Base\BaseTache;

class Tache extends BaseTache
{ 

     protected $with = [
       'prioriteTache',
    ];

    public function __toString()
    {
        return ($this->prioriteTache ? ($this->prioriteTache->nom . "-") : "") . ($this->titre ?? "");
    }

    public function generateReference(): string
    {
         return  $this->projet->reference . "-" .  $this->titre;
    }

}
