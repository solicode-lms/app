<?php


namespace Modules\PkgCreationProjet\Models;
use Modules\PkgCreationProjet\Models\Base\BaseLivrable;

class Livrable extends BaseLivrable
{
   protected $with = [
       'natureLivrable',
    ];

}
