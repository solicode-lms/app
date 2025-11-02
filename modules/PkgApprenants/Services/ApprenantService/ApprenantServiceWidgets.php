<?php

namespace Modules\PkgApprenants\Services\ApprenantService;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgAutorisation\Models\Role;

trait ApprenantServiceWidgets
{

// Widgets - DataSource 


    public function apprenantSansTacheTermineeDepuis7JoursQuery(): Builder
    {
        $user = Auth::user();

        $query = Apprenant::where(function ($q) {
            $q->whereRaw('(SELECT TIMESTAMPDIFF(DAY, MAX(rt.updated_at), NOW())
                FROM realisation_taches rt
                JOIN realisation_projets rp ON rt.realisation_projet_id = rp.id
                JOIN etat_realisation_taches ert ON rt.etat_realisation_tache_id = ert.id
                JOIN workflow_taches wt ON ert.workflow_tache_id = wt.id
                WHERE rp.apprenant_id = apprenants.id
                AND wt.code IN ("APPROVED", "TO_APPROVE","NOT_VALIDATED")
            ) >= 7');
        })->orWhere(function ($q) {
            $q->whereRaw('(SELECT COUNT(*)
                FROM realisation_taches rt
                JOIN realisation_projets rp ON rt.realisation_projet_id = rp.id
                JOIN etat_realisation_taches ert ON rt.etat_realisation_tache_id = ert.id
                JOIN workflow_taches wt ON ert.workflow_tache_id = wt.id
                WHERE rp.apprenant_id = apprenants.id
                AND wt.code IN ("APPROVED", "TO_APPROVE","NOT_VALIDATED")
            ) = 0');
        });

        // Restreindre selon les rôles
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

        return $query;
    }

    public function apprenantSansTacheTermineeDepuis7Jours()
    {
        $query = $this->apprenantSansTacheTermineeDepuis7JoursQuery();

        return $query->get()->map(function ($apprenant) {

            // 🧮 Récupération de la durée sans tâche terminée (en heures)
            $heures = (int) $apprenant->duree_sans_terminer_tache;

            if ($heures > 0) {
                // Conversion heures → jours + heures
                $jours = floor($heures / 24);
                $reste_heures = $heures % 24;
                $duree = "{$jours} jours {$reste_heures} heures";
            } else {
                $duree = "Aucune tâche terminée";
            }

            return [
                'apprenant' => $apprenant,
                'groupe' => $apprenant->groupes?->pluck('code')->implode(', ') ?? '—',
                'duree' => $duree,
            ];
        })->toArray(); // ✅ Conversion finale en tableau associatif
    }

    public function getApprenantSansTacheAFaireQuery(): Builder
    {
        $user = Auth::user();

        $query = Apprenant::whereDoesntHave('realisationProjets.realisationTaches.etatRealisationTache.workflowTache', function ($q) {
            $q->where('code', 'TODO'); // <- Référence logique
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

        return $query;
    }

    public function getApprenantSansTacheAFaire()
    {
        $query = $this->getApprenantSansTacheAFaireQuery();

        return $query->get()->map(function ($apprenant) {

            // 🧮 Récupération de la durée depuis la dernière tâche terminée ou en validation
            $heures = (int) $apprenant->duree_sans_terminer_tache;

            if ($heures > 0) {
                // Conversion des heures en jours + heures
                $jours = floor($heures / 24);
                $reste_heures = $heures % 24;
                $duree = "{$jours} jours {$reste_heures} heures";
            } else {
                $duree = "Aucune tâche terminée";
            }

            return [
                'apprenant' => $apprenant,
                'groupe' => $apprenant->groupes?->pluck('code')->implode(', ') ?? '—',
                'duree' => $duree,
            ];
        })->toArray(); // ✅ Conversion finale en tableau associatif
    }


    public function apprenantSansTacheEnCoursQuery(): Builder
    {
        $user = Auth::user();

        $query = Apprenant::whereDoesntHave('realisationProjets.realisationTaches.etatRealisationTache.workflowTache', function ($q) {
            $q->where('code', 'IN_PROGRESS'); // <- Référence logique
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

        return $query;
    }

    public function getApprenantSansTacheEnCours()
    {
        $query = $this->apprenantSansTacheEnCoursQuery();

        return $query->get()->map(function ($apprenant) {

            // 🕒 Dernière activité (réalisée par l'apprenant)
            $derniere_activite = $apprenant->derniere_activite;

            if ($derniere_activite) {
                try {
                    $derniere_activite = \Carbon\Carbon::parse($derniere_activite)->diffForHumans();
                } catch (\Exception $e) {
                    $derniere_activite = $apprenant->derniere_activite; // fallback brut
                }
            } else {
                $derniere_activite = "Aucune activité";
            }

            return [
                'apprenant' => $apprenant,
                'groupe' => $apprenant->groupes?->pluck('code')->implode(', ') ?? '—',
                'derniere_activite' => $derniere_activite, // ✅ remplacement
            ];
        })->toArray(); // ✅ Conversion finale en tableau associatif
    }



    public function apprenantAvecTacheEnCoursQuery(): Builder
    {
        $user = Auth::user();

        $query = Apprenant::whereHas('realisationProjets.realisationTaches.etatRealisationTache.workflowTache', function ($q) {
            $q->where('code', 'IN_PROGRESS'); // <- Référence logique
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

        return $query;
    }

    public function apprenantAvecTacheEnCours()
    {
        
        $query = $this->apprenantAvecTacheEnCoursQuery();

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
                'groupe' => $apprenant->groupes?->pluck('code')->implode(', '),
                'duree' => $duree,
            ];
        })->toArray(); // <-- Conversion finale en tableau associatif
    }

    public function apprenantAvecTacheAFaireQuery(): Builder
    {
        $user = Auth::user();

        $query = Apprenant::whereHas('realisationProjets.realisationTaches.etatRealisationTache.workflowTache', function ($q) {
            $q->where('code', 'TODO'); // <- Référence logique
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

        return $query;
    }

    public function apprenantAvecTacheAFaire()
    {
        
        $query = $this->apprenantAvecTacheAFaireQuery();

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
                'groupe' => $apprenant->groupes?->pluck('code')->implode(', '),
                'duree' => $duree,
            ];
        })->toArray(); // <-- Conversion finale en tableau associatif
    }
}