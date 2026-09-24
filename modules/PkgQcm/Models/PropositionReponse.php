<?php



namespace Modules\PkgQcm\Models;
use Modules\PkgQcm\Models\Base\BasePropositionReponse;

class PropositionReponse extends BasePropositionReponse
{
    /**
     * Surcharge de __toString pour afficher si la proposition est correcte.
     *
     * @return string
     */
    public function __toString()
    {
        $etat = $this->is_correcte ? '✅' : '❌';
        return "$etat" . ($this->libelle ?? "") ;
    }
}
