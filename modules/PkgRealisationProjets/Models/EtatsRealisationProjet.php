<?php


namespace Modules\PkgRealisationProjets\Models;
use Modules\PkgRealisationProjets\Models\Base\BaseEtatsRealisationProjet;

class EtatsRealisationProjet extends BaseEtatsRealisationProjet
{

    protected $with = [
        'sysColor',
    ];

    // public function __toString()
    // {
    //     return ($this->titre ?? "") . "-" . ($this->formateur?->nom ?? "");
    // }

    public function generateReference(): string
    {

        return $this->titre;
    }

     public function __toString()
    {
        return $this->titre ?? "";
    }
}
