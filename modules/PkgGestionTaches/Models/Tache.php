<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Models;
use Modules\PkgGestionTaches\Models\Base\BaseTache;

class Tache extends BaseTache
{
    public function __toString()
    {
        return ($this->prioriteTache ? ($this->prioriteTache->nom . "_") : "") . ($this->titre ?? "");
    }
    
}
