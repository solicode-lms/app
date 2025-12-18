<?php


namespace Modules\PkgRealisationProjets\Models;
use Modules\PkgRealisationProjets\Models\Base\BaseLivrablesRealisation;

class LivrablesRealisation extends BaseLivrablesRealisation
{
    protected $with = [
        'livrable',
    ];

    // public function generateReference(): string
    // {
    //     return $this->realisationProjet->reference . "-" . $this->livrable->reference ;
    // }
}
