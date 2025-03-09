<?php

namespace Modules\PkgApprenants\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Services\Base\BaseApprenantService;
use Modules\PkgAutorisation\Models\Role;
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


    public function paginate(array $params = [], int $perPage = 0, array $columns = ['*']): LengthAwarePaginator
    {
        $perPage = $perPage ?: $this->paginationLimit;

        return $this->model::withScope(function () use ($params, $perPage, $columns) {
           
            $query = $this->model->newQuery();

            // Ajouter le champ dynamique nombre_realisation_taches_en_cours
            $query->withCount([
                'realisationProjets as nombre_realisation_taches_en_cours' => function ($subQuery) {
                    $subQuery->whereHas('realisationTaches', function ($q) {
                        $q->whereHas('etatRealisationTache', function ($etat) {
                            $etat->where('nom', 'En cours'); // Filtrer uniquement les tâches en cours
                        });
                    });
                }
            ]);

            // $query->withCount([
            //     'realisationProjets as nombre_realisation_taches_en_cours' => function ($subQuery) {
            //         $apprenant = new Apprenant(); // Instancier un objet pour appeler la méthode
            //         $subQuery->mergeConstraintsFrom($apprenant->queryRealisationTachesEnCours());
            //     }
            // ]);


            $query = $this->allQuery($params, $query);

            // $query->orderBy('nombre_realisation_taches_en_cours', 'asc');
          

            // Calcul du nombre total des résultats filtrés
            $this->totalFilteredCount = $query->count();

            return $query->paginate($perPage, $columns);
        });
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


/**
 * Récupérer la liste des apprenants qui n'ont aucune tâche en cours,
 * en fonction du rôle de l'utilisateur connecté.
 *
 * @return \Illuminate\Database\Eloquent\Collection
 */
public function getApprenantQuiNonPasTacheEnCours()
{
    $user = Auth::user();

    // Base de la requête : filtrer les apprenants qui n'ont pas de tâche en cours
    $query = Apprenant::whereDoesntHave('realisationProjets', function ($query) {
        $query->whereHas('realisationTaches', function ($q) {
            $q->whereHas('etatRealisationTache', function ($etat) {
                $etat->where('nom', 'En cours');
            });
        });
    });

    // Appliquer les filtres en fonction du rôle
    if ($user->hasRole(Role::APPRENANT_ROLE)) {
        // Récupérer les apprenants du même groupe
        $query->whereHas('groupes', function ($groupQuery) use ($user) {
            $groupQuery->whereHas('apprenants', function ($apprenantQuery) use ($user) {
                $apprenantQuery->where('id', $user->apprenant->id);
            });
        });

    } elseif ($user->hasRole(Role::FORMATEUR_ROLE)) {
        // Récupérer les apprenants des groupes encadrés par le formateur
        $query->whereHas('groupes', function ($groupQuery) use ($user) {
            $groupQuery->whereHas('formateurs', function ($formateurQuery) use ($user) {
                $formateurQuery->where('id', $user->formateur->id);
            });
        });
    }

    return $query->get();
}


   
}
