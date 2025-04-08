<?php

namespace Modules\PkgGestionTaches\Services\RealisationTacheService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgAutorisation\Models\Role;

trait RealisationTacheServiceWidgets
{

// Widgets - DataSource 

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


public function getApprenantSansTacheEnCours()
{
    $user = Auth::user();

    $query = Apprenant::whereDoesntHave('realisationProjets.realisationTaches.etatRealisationTache.workflowTache', function ($q) {
        $q->where('code', 'EN_COURS'); // <- Référence logique
    });

    if ($user->hasRole(Role::APPRENANT_ROLE)) {
        $query->whereHas('groupes.apprenants', function ($q) use ($user) {
            $q->where('id', $user->apprenant->id);
        });
    }

    if ($user->hasRole(Role::FORMATEUR_ROLE)) {
        $query->whereHas('groupes.formateurs', function ($q) use ($user) {
            $q->where('id', $user->formateur->id);
        });
    }

    // return $query->get();

    return $query->get()->map(function ($apprenant) {

        // On cherche la dernière tâche "terminée" ou "en validation"
        $derniere_tache_terminee_ou_validation = $apprenant->derniere_tache_terminee_ou_validation;
        $date = optional($derniere_tache_terminee_ou_validation)->updated_at;
       
        if ($date) { // ✅ Vérifier que la date est passée
            $diff = $date->diff(now());
            $jours = $diff->d;
            $heures = $diff->h;
            $duree = "{$jours} jours {$heures} heures";
        }else{
            $duree = "Aucune tâche terminée";
        }
    
        return [
            'apprenant' => $apprenant,
            'groupe' => $apprenant->groupe,
            'duree' => $duree,
        ];
    })->toArray(); // <-- Conversion finale en tableau associatif
}

}