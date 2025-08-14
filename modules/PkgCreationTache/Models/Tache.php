<?php
 

namespace Modules\PkgCreationTache\Models;
use Modules\PkgCreationTache\Models\Base\BaseTache;

class Tache extends BaseTache
{ 

     protected $with = [];

    public function __toString()
    {
        return ($this->priorite ? $this->priorite . ' - ' : '') . ($this->titre ?? '');
    }

    public function generateReference(): string
    {
         return  $this->projet->reference . "-" .  $this->titre;
    }

}
