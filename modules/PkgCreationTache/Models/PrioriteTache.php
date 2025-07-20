<?php


namespace Modules\PkgCreationTache\Models;
use Modules\PkgCreationTache\Models\Base\BasePrioriteTache;

class PrioriteTache extends BasePrioriteTache
{
    public function generateReference(): string
    {
         return  $this->nom;
    }

}
