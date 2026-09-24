<?php
namespace Modules\PkgQcm\Models;
use Modules\PkgQcm\Models\Base\BaseAffectationQcmProjet;

class AffectationQcmProjet extends BaseAffectationQcmProjet
{
    /**
     * Représentation textuelle de l'affectation du QCM au Projet.
     * 
     * @return string
     */
    public function __toString()
    {
        $qcmStr = $this->qcm ? (string) $this->qcm : 'QCM';
        $projetStr = $this->affectationProjet ? (string) $this->affectationProjet : 'Projet';

        $label = trim("$qcmStr - $projetStr", ' -');
        
        return $label ?: (string) ($this->reference ?? $this->id ?? "");
    }
}
