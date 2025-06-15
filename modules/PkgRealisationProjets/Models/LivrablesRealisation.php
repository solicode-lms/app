<?php


namespace Modules\PkgRealisationProjets\Models;
use Modules\PkgRealisationProjets\Models\Base\BaseLivrablesRealisation;

class LivrablesRealisation extends BaseLivrablesRealisation
{
    protected $with = [
        'livrable',
    ];
}
