<?php

namespace Modules\PkgCreationProjet\Models;
use Modules\PkgCreationProjet\Models\Base\BaseResource;

class Resource extends BaseResource
{

    public function generateReference(): string
    {
         return  $this->projet->reference . "-" .  $this->nom;
    }
}
