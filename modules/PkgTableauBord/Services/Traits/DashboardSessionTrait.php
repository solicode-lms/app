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
                    $projets = Projet::whereHas('affectationProjets.realisationProjets', function ($q) use ($apprenant) {
                        $q->where('apprenant_id', $apprenant->id);
                    })
                    ->with(['formateur', 'filiere'])
                    ->orderBy('titre')
                    ->get();

                    if ($projets->isNotEmpty()) {
                        return $projets;
                    }
                }
            }

            // Pour un formateur : projets dont il est le formateur référent
            if ($user->hasRole('formateur')) {
                $formateur = Formateur::where('user_id', $user->id)->first();
                if ($formateur) {
                    $projets = Projet::where('formateur_id', $formateur->id)
                        ->with(['formateur', 'filiere'])
                        ->orderBy('titre')
                        ->get();

                    if ($projets->isNotEmpty()) {
                        return $projets;
                    }
                }
            }
        }

        // Pour un responsable ou par défaut : tous les projets avec formateur
        return Projet::with(['formateur', 'filiere'])
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
        // 1. Si expressément demandé par la requête
        if (!empty($requestedProjetId)) {
            $projet = Projet::with(['formateur', 'filiere'])->find($requestedProjetId);
            if ($projet) {
                session(['active_projet_id' => $projet->id]);
                return $projet;
            }
        }

        // 2. Si un projet est mémorisé dans la session PHP
        $storedId = session('active_projet_id');
        if (!empty($storedId)) {
            $projet = Projet::with(['formateur', 'filiere'])->find($storedId);
            if ($projet) {
                return $projet;
            }
        }

        // 3. Détermination par profil
        $projet = null;

        if ($user) {
            // Apprenant : son projet actif le plus récent
            if ($user->hasRole('apprenant')) {
                $dernierRp = RealisationProjet::whereHas('apprenant', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->latest('date_debut')
                ->first();

                if ($dernierRp && $dernierRp->affectationProjet?->projet_id) {
                    $projet = Projet::with(['formateur', 'filiere'])->find($dernierRp->affectationProjet->projet_id);
                }
            }

            // Formateur : son projet le plus récent
            if (!$projet && $user->hasRole('formateur')) {
                $formateur = Formateur::where('user_id', $user->id)->first();
                if ($formateur) {
                    $projet = Projet::where('formateur_id', $formateur->id)
                        ->with(['formateur', 'filiere'])
                        ->latest('id')
                        ->first();
                }
            }
        }

        // 4. Fallback général : le projet ayant des affectations le plus récent
        if (!$projet) {
            $projet = Projet::whereHas('affectationProjets')
                ->with(['formateur', 'filiere'])
                ->latest('id')
                ->first();
        }

        if (!$projet) {
            $projet = Projet::with(['formateur', 'filiere'])->first();
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
