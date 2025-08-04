<?php
 

namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationMicroCompetence;

class RealisationMicroCompetence extends BaseRealisationMicroCompetence
{

    protected $with = [
        'apprenant',
        'microCompetence',
        'etatRealisationMicroCompetence'
    ];


    protected static function booted()
    {
        static::saving(function ($model) {
            $model->dernier_update = now();
        });
    }

    public function __toString()
    {
        return $this->microCompetence->titre . "-" . $this->apprenant;
    }

}
