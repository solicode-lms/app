<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Illuminate\Database\Eloquent\Collection;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationProjets\Models\AffectationProjet;

trait DashboardSessionTrait
{
    /**
     * Récupère la liste des Projets (sessions de formation concrètes du formateur)
     * disponibles selon le rôle de l'utilisateur.
     *
     * @param User|null $user
     * @return Collection
     */
    public function getSessionsProjetDisponibles(?User $user = null): Collection
    {
        if ($user) {
            // Pour un apprenant : projets auxquels il est inscrit
            if ($user->hasRole('apprenant')) {
                $apprenant = Apprenant::where('user_id', $user->id)->first();
                if ($apprenant) {
                    return Projet::sessionsFormation()
                        ->whereHas('affectationProjets.realisationProjets', function ($q) use ($apprenant) {
                            $q->where('apprenant_id', $apprenant->id);
                        })
                        ->with(['formateur', 'filiere'])
                        ->orderBy('titre')
                        ->get();
                }
                return new Collection();
            }

            // Pour un formateur : projets dont il est le formateur référent (strictement cloisonné)
            if ($user->hasRole('formateur')) {
                $formateur = Formateur::where('user_id', $user->id)->first();
                if ($formateur) {
                    return Projet::sessionsFormation()
                        ->where('formateur_id', $formateur->id)
                        ->with(['formateur', 'filiere'])
                        ->orderBy('titre')
                        ->get();
                }
                return new Collection();
            }
        }

        // Pour un responsable ou par défaut : tous les projets session de formation avec formateur
        return Projet::sessionsFormation()
            ->with(['formateur', 'filiere'])
            ->whereHas('affectationProjets')
            ->orderBy('titre')
            ->get();
    }

    /**
     * Résout le Projet actif (session de projet) demandé par l'acteur ou déterminé par défaut.
     *
     * @param int|null $requestedProjetId
     * @param User|null $user
     * @return Projet|null
     */
    public function resolveActiveProjet(?int $requestedProjetId = null, ?User $user = null): ?Projet
    {
        // Traitement dédié et hermétique pour le formateur
        if ($user && $user->hasRole('formateur')) {
            $formateur = Formateur::where('user_id', $user->id)->first();
            if (!$formateur) {
                return null;
            }

            // 1. Si expressément demandé par la requête, vérifier qu'il appartient bien à ce formateur
            if (!empty($requestedProjetId)) {
                $projet = Projet::sessionsFormation()
                    ->where('formateur_id', $formateur->id)
                    ->with(['formateur', 'filiere'])
                    ->find($requestedProjetId);
                if ($projet) {
                    session(['active_projet_id' => $projet->id]);
                    return $projet;
                }
            }

            // 2. Si un projet est mémorisé en session PHP, vérifier qu'il appartient bien à ce formateur
            $storedId = session('active_projet_id');
            if (!empty($storedId)) {
                $projet = Projet::sessionsFormation()
                    ->where('formateur_id', $formateur->id)
                    ->with(['formateur', 'filiere'])
                    ->find($storedId);
                if ($projet) {
                    return $projet;
                }
            }

            // 3. Projet session le plus récent de ce formateur
            $projet = Projet::sessionsFormation()
                ->where('formateur_id', $formateur->id)
                ->with(['formateur', 'filiere'])
                ->latest('id')
                ->first();

            if ($projet) {
                session(['active_projet_id' => $projet->id]);
            }

            return $projet;
        }

        // Traitement dédié et hermétique pour l'apprenant
        if ($user && $user->hasRole('apprenant')) {
            $apprenant = Apprenant::where('user_id', $user->id)->first();
            if (!$apprenant) {
                return null;
            }

            // 1. Si expressément demandé par la requête, vérifier que l'apprenant y est bien inscrit
            if (!empty($requestedProjetId)) {
                $projet = Projet::sessionsFormation()
                    ->whereHas('affectationProjets.realisationProjets', function ($q) use ($apprenant) {
                        $q->where('apprenant_id', $apprenant->id);
                    })
                    ->with(['formateur', 'filiere'])
                    ->find($requestedProjetId);
                if ($projet) {
                    session(['active_projet_id' => $projet->id]);
                    return $projet;
                }
            }

            // 2. Si un projet est mémorisé en session PHP, vérifier que l'apprenant y est bien inscrit
            $storedId = session('active_projet_id');
            if (!empty($storedId)) {
                $projet = Projet::sessionsFormation()
                    ->whereHas('affectationProjets.realisationProjets', function ($q) use ($apprenant) {
                        $q->where('apprenant_id', $apprenant->id);
                    })
                    ->with(['formateur', 'filiere'])
                    ->find($storedId);
                if ($projet) {
                    return $projet;
                }
            }

            // 3. Projet le plus récent de l'apprenant
            $dernierRp = RealisationProjet::where('apprenant_id', $apprenant->id)
                ->whereHas('affectationProjet.projet', function ($q) {
                    $q->sessionsFormation();
                })
                ->latest('date_debut')
                ->first();

            $projet = null;
            if ($dernierRp && $dernierRp->affectationProjet?->projet_id) {
                $projet = Projet::sessionsFormation()->with(['formateur', 'filiere'])->find($dernierRp->affectationProjet->projet_id);
            }

            if ($projet) {
                session(['active_projet_id' => $projet->id]);
            }

            return $projet;
        }

        // 1. Si expressément demandé par la requête (Responsable / Admin)
        if (!empty($requestedProjetId)) {
            $projet = Projet::sessionsFormation()->with(['formateur', 'filiere'])->find($requestedProjetId);
            if ($projet) {
                session(['active_projet_id' => $projet->id]);
                return $projet;
            }
        }

        // 2. Si un projet est mémorisé dans la session PHP
        $storedId = session('active_projet_id');
        if (!empty($storedId)) {
            $projet = Projet::sessionsFormation()->with(['formateur', 'filiere'])->find($storedId);
            if ($projet) {
                return $projet;
            }
        }

        // 3. Projet session ayant des affectations le plus récent (Responsable / Admin)
        $projet = Projet::sessionsFormation()
            ->whereHas('affectationProjets')
            ->with(['formateur', 'filiere'])
            ->latest('id')
            ->first();

        if (!$projet) {
            $projet = Projet::sessionsFormation()->with(['formateur', 'filiere'])->first();
        }

        if ($projet) {
            session(['active_projet_id' => $projet->id]);
        }

        return $projet;
    }

    /**
     * Alias de compatibilité pour getSessionsDisponibles.
     */
    public function getSessionsDisponibles(?User $user = null): Collection
    {
        return $this->getSessionsProjetDisponibles($user);
    }

    /**
     * Alias de compatibilité pour resolveActiveSessionFormation.
     */
    public function resolveActiveSessionFormation(?int $requestedId = null, ?User $user = null): ?Projet
    {
        return $this->resolveActiveProjet($requestedId, $user);
    }
}
