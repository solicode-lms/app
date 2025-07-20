<?php


namespace Modules\PkgCreationProjet\Models;
use Modules\PkgCreationProjet\Models\Base\BaseLivrable;

class Livrable extends BaseLivrable
{
    protected $with = [
       'natureLivrable',
    ];

    public function generateReference(): string
    {
         return  $this->projet->reference . "-" .  $this->titre;
    }

}
