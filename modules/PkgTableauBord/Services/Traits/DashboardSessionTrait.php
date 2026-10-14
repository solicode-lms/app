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
     * Récupère la liste des Projets disponibles selon le rôle de l'utilisateur.
     *
     * @param User|null $user
     * @param bool $onlySessions Si vrai, restreint aux sessions de formation N2
     * @return Collection
     */
    public function getSessionsProjetDisponibles(?User $user = null, bool $onlySessions = false): Collection
    {
        if ($user) {
            // Pour un apprenant : projets auxquels il est inscrit
            if ($user->hasRole('apprenant')) {
                $apprenant = Apprenant::where('user_id', $user->id)->first();
                if ($apprenant) {
                    $q = Projet::query()
                        ->whereHas('affectationProjets.realisationProjets', function ($sub) use ($apprenant) {
                            $sub->where('apprenant_id', $apprenant->id);
                        })
                        ->with(['formateur', 'filiere'])
                        ->withExists(['taches as has_live_coding' => function ($sub) {
                            $sub->whereHas('phaseEvaluation', fn($pe) => $pe->where('code', 'N2'));
                        }])
                        ->orderBy('titre');
                    if ($onlySessions) {
                        $q->sessionsFormation();
                    }
                    return $q->get();
                }
                return new Collection();
            }

            // Pour un formateur : projets dont il est le formateur référent
            if ($user->hasRole('formateur')) {
                $formateur = Formateur::where('user_id', $user->id)->first();
                if ($formateur) {
                    $q = Projet::query()
                        ->where('formateur_id', $formateur->id)
                        ->with(['formateur', 'filiere'])
                        ->withExists(['taches as has_live_coding' => function ($sub) {
                            $sub->whereHas('phaseEvaluation', fn($pe) => $pe->where('code', 'N2'));
                        }])
                        ->orderBy('titre');
                    if ($onlySessions) {
                        $q->sessionsFormation();
                    }
                    return $q->get();
                }
                return new Collection();
            }
        }

        // Pour un responsable ou par défaut : tous les projets ayant des affectations
        $q = Projet::query()
            ->with(['formateur', 'filiere'])
            ->whereHas('affectationProjets')
            ->withExists(['taches as has_live_coding' => function ($sub) {
                $sub->whereHas('phaseEvaluation', fn($pe) => $pe->where('code', 'N2'));
            }])
            ->orderBy('titre');
        if ($onlySessions) {
            $q->sessionsFormation();
        }
        return $q->get();
    }

    /**
     * Résout le Projet actif demandé par l'acteur ou déterminé par défaut.
     *
     * @param int|null $requestedProjetId
     * @param User|null $user
     * @param bool $fallbackToLatestSession Si faux, retourne null si non demandé (ex: vision globale responsable)
     * @return Projet|null
     */
    public function resolveActiveProjet(?int $requestedProjetId = null, ?User $user = null, bool $fallbackToLatestSession = true): ?Projet
    {
        // Traitement dédié et hermétique pour le formateur
        if ($user && $user->hasRole('formateur')) {
            $formateur = Formateur::where('user_id', $user->id)->first();
            if (!$formateur) {
                return null;
            }

            // 1. Si expressément demandé par la requête, vérifier qu'il appartient bien à ce formateur
            if (!empty($requestedProjetId)) {
                $projet = Projet::where('formateur_id', $formateur->id)
                    ->with(['formateur', 'filiere'])
                    ->find($requestedProjetId);
                if ($projet) {
                    session(['active_projet_id' => $projet->id]);
                    return $projet;
                }
            }

            if (!$fallbackToLatestSession) {
                return null;
            }

            // 2. Si un projet est mémorisé en session PHP
            $storedId = session('active_projet_id');
            if (!empty($storedId)) {
                $projet = Projet::where('formateur_id', $formateur->id)
                    ->with(['formateur', 'filiere'])
                    ->find($storedId);
                if ($projet) {
                    return $projet;
                }
            }

            // 3. Projet session N2 le plus récent de ce formateur
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

            // 1. Si expressément demandé par la requête
            if (!empty($requestedProjetId)) {
                $projet = Projet::whereHas('affectationProjets.realisationProjets', function ($q) use ($apprenant) {
                        $q->where('apprenant_id', $apprenant->id);
                    })
                    ->with(['formateur', 'filiere'])
                    ->find($requestedProjetId);
                if ($projet) {
                    session(['active_projet_id' => $projet->id]);
                    return $projet;
                }
            }

            if (!$fallbackToLatestSession) {
                return null;
            }

            // 2. Si un projet est mémorisé en session PHP
            $storedId = session('active_projet_id');
            if (!empty($storedId)) {
                $projet = Projet::whereHas('affectationProjets.realisationProjets', function ($q) use ($apprenant) {
                        $q->where('apprenant_id', $apprenant->id);
                    })
                    ->with(['formateur', 'filiere'])
                    ->find($storedId);
                if ($projet) {
                    return $projet;
                }
            }

            // 3. Projet session N2 le plus récent de l'apprenant
            $dernierRp = RealisationProjet::where('apprenant_id', $apprenant->id)
                ->whereHas('affectationProjet.projet', function ($q) {
                    $q->sessionsFormation();
                })
                ->latest('date_debut')
                ->first();

            $projet = null;
            if ($dernierRp && $dernierRp->affectationProjet?->projet_id) {
                $projet = Projet::with(['formateur', 'filiere'])->find($dernierRp->affectationProjet->projet_id);
            }

            if ($projet) {
                session(['active_projet_id' => $projet->id]);
            }

            return $projet;
        }

        // 1. Si expressément demandé par la requête (Responsable / Admin)
        if (!empty($requestedProjetId)) {
            $projet = Projet::with(['formateur', 'filiere'])->find($requestedProjetId);
            if ($projet) {
                return $projet;
            }
        }

        // 2. Si fallback désactivé (ex: mode vision globale du responsable), retourner null
        if (!$fallbackToLatestSession) {
            return null;
        }

        // 3. Projet session N2 ayant des affectations le plus récent
        $projet = Projet::sessionsFormation()
            ->whereHas('affectationProjets')
            ->with(['formateur', 'filiere'])
            ->latest('id')
            ->first();

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
