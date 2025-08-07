<?php
 

namespace Modules\PkgSessions\Services;
use Modules\PkgSessions\Services\Base\BaseSessionFormationService;

/**
 * Classe SessionFormationService pour gérer la persistance de l'entité SessionFormation.
 */
class SessionFormationService extends BaseSessionFormationService
{
   

    public function add_projet(int $sessionFormationId)
    {
        $sessionFormation = $this->find($sessionFormationId);
        if (!$sessionFormation) {
            return false; 
        }
        $value =  $sessionFormation->save();
        $this->pushServiceMessage("info","Traitement title", "message : résultat de traitement");
        return $value;
    }
   
}
