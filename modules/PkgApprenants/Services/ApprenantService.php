<?php

namespace Modules\PkgApprenants\Services;

use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Services\Base\BaseApprenantService;
use Modules\PkgAutorisation\Services\UserService;

/**
 * Classe ApprenantService pour gérer la persistance de l'entité Apprenant.
 */
class ApprenantService extends BaseApprenantService
{
    public function dataCalcul($apprenant)
    {
        // En Cas d'édit
        if(isset($apprenant->id)){
          
        }
      
        return $apprenant;
    }

    public function initPassword(int $apprenantId)
    {
        $apprenant = $this->find($apprenantId);
        if (!$apprenant) {
            return false; 
        }
        $userService = new UserService();
        $value = $userService->initPassword($apprenant->user->id);
        return $value;
    }

/**
 * Trouver la liste des apprenants appartenant aux mêmes groupes qu'un apprenant donné.
 *
 * @param int $apprenantId
 * @return \Illuminate\Database\Eloquent\Collection
 */
public function getApprenantsDeGroupe($apprenantId)
{
    return Apprenant::whereHas('groupes', function ($query) use ($apprenantId) {
        $query->whereHas('apprenants', function ($q) use ($apprenantId) {
            $q->where('apprenants.id', $apprenantId);
        });
    })->get();
}
   
}
