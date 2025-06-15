<?php


namespace Modules\PkgRealisationProjets\Models;
use Modules\PkgRealisationProjets\Models\Base\BaseEtatsRealisationProjet;

class EtatsRealisationProjet extends BaseEtatsRealisationProjet
{

    protected $with = [
        'sysColor',
    ];

    public static $user_column_name = "formateur_id";

    // public function __toString()
    // {
    //     return ($this->titre ?? "") . "-" . ($this->formateur?->nom ?? "");
    // }
}
