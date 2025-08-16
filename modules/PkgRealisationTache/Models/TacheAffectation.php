<?php


namespace Modules\PkgRealisationTache\Models;
use Modules\PkgRealisationTache\Models\Base\BaseTacheAffectation;

class TacheAffectation extends BaseTacheAffectation
{
    protected $casts = [
        'apprenant_live_coding_cache' => 'array',
    ];

}
