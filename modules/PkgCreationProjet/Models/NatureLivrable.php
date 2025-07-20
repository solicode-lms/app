<?php


namespace Modules\PkgCreationProjet\Models;
use Modules\PkgCreationProjet\Models\Base\BaseNatureLivrable;

class NatureLivrable extends BaseNatureLivrable
{
    public function generateReference(): string
    {
         return   $this->nom;
    }

}
