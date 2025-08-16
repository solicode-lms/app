<?php
 

namespace Modules\PkgRealisationTache\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\PkgRealisationTache\Models\Base\BaseEtatRealisationTache;

class EtatRealisationTache extends BaseEtatRealisationTache
{
    protected $with = [
       'workflowTache',
       'sysColor',
    ];

    protected static function booted()
    {
        static::addGlobalScope('ordre', function (Builder $builder) {
            $builder->orderBy('ordre', 'asc'); // ou la colonne que tu veux
        });
    }

   

}
