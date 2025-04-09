<?php

namespace Modules\PkgApprenants\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Services\Base\BaseApprenantService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgFormation\Models\Filiere;

/**
 * Classe ApprenantService pour gérer la persistance de l'entité Apprenant.
 */
class ApprenantService extends BaseApprenantService
{

    public function find(int $id, array $columns = ['*']){
        return $this->model::withoutGlobalScope('inactif')->find($id);
    }
    // protected function updateRecord(Model $record, array $data): void
    // {
    //     $record->update($data);
    // }
    
    public function dataCalcul($apprenant)
    {
        // En Cas d'édit
        if(isset($apprenant->id)){
          
        }
      
        return $apprenant;
    }

    public function edit(int $id){

        return $this->model::withoutGlobalScope('inactif')->findOrFail($id);
    }

    // public function initFieldsFilterable(){

    //     // Initialiser les filtres configurables dynamiquement
    //     $scopeVariables = $this->viewState->getScopeVariables('apprenant');
    //     $this->fieldsFilterable = [];
    
     

    //     // TODO Gapp : à générer depuis metaData : relationFilter
    //     $this->fieldsFilterable[] = $this->generateRelationFilter(
    //         __("PkgFormation::Filiere.plural"), 
    //         'groupes.filiere_id', 
    //         Filiere::class, 
    //         "id",
    //         null,
    //         "[name='groupe_id']",
    //         route('groupes.getData'),
    //         "filiere_id"
    //     );

    //     if (!array_key_exists('groupes', $scopeVariables)) {
    //         $this->fieldsFilterable[] = $this->generateManyToManyFilter(__("PkgApprenants::groupe.plural"), 'groupe_id', \Modules\PkgApprenants\Models\Groupe::class, 'code');
    //         }


    //         if (!array_key_exists('niveaux_scolaire_id', $scopeVariables)) {
    //             $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgApprenants::niveauxScolaire.plural"), 'niveaux_scolaire_id', \Modules\PkgApprenants\Models\NiveauxScolaire::class, 'code');
    //             }
              
    // }

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
