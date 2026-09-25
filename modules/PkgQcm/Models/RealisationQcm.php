<?php



namespace Modules\PkgQcm\Models;
use Modules\PkgQcm\Models\Base\BaseRealisationQcm;

class RealisationQcm extends BaseRealisationQcm
{
    public function __toString()
    {
        $qcm = $this->qcm ?? 'QCM inconnu';
        $apprenant = $this->apprenant ?? 'Apprenant inconnu';
        return "{$qcm} - {$apprenant}";
    }
}
