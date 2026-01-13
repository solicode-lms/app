<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationTache\Models;
use Modules\PkgCreationTache\Models\Base\BasePhaseProjet;

class PhaseProjet extends BasePhaseProjet
{
    public function generateReference(): string
    {
        return $this->code ;
    }
}
