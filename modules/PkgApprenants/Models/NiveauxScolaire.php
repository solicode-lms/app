<?php


namespace Modules\PkgApprenants\Models;
use Modules\PkgApprenants\Models\Base\BaseNiveauxScolaire;

class NiveauxScolaire extends BaseNiveauxScolaire
{
    public function generateReference(): string
    {
        return $this->code ;
    }
}
