<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Models;
use Modules\PkgApprenants\Models\Base\BaseSousGroupe;

class SousGroupe extends BaseSousGroupe
{
     protected $with = [
        'groupe'
    ];

}
