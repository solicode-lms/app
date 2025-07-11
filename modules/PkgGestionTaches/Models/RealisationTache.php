<?php
 

namespace Modules\PkgRealisationTache\Models;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Models\Livrable;
use Modules\PkgRealisationTache\Models\Base\BaseRealisationTache;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgRealisationProjets\Models\LivrablesRealisation;
use Modules\PkgRealisationProjets\Models\RealisationProjet;

class RealisationTache extends BaseRealisationTache
{

    protected $with = [
       'tache.livrables',
       'realisationProjet.apprenant',
       'etatRealisationTache',
       'livrablesRealisations'
    ];
   
/**
 *  Remplacé par livrablesRealisations pour optimisation
 * Récupérer les réalisations des livrables associés à la tâche de cette réalisation,
 * uniquement pour l'apprenant lié à cette RealisationTache.
 *
 * @return \Illuminate\Database\Eloquent\Collection
 */
public function getRealisationLivrable()
{
    return LivrablesRealisation::whereHas('livrable', function ($query) {
            $query->whereHas('taches', function ($q) {
                $q->where('id', $this->tache_id);
            });
        })
        ->whereHas('realisationProjet', function ($q) {
            $q->where('apprenant_id', $this->realisationProjet->apprenant_id);
        })
        ->get();
}

    public function livrablesRealisations():HasManyThrough
    {
        // dd($this->id);
        $resultat =  $this->hasManyThrough(
            LivrablesRealisation::class,
            RealisationProjet::class,
            'id', // foreign key on RealisationProjet
            'realisation_projet_id', // foreign key on LivrablesRealisation
            'realisation_projet_id', // local key on RealisationTache
            'id' // local key on RealisationProjet
        );
        return $resultat;
    }

    public function __toString()
    {
        return ($this->tache?->titre ?? "") .  " - ". $this->realisationProjet?->apprenant ?? "";
    }

    public function getRevisionsBeforePriority(): \Illuminate\Database\Eloquent\Collection
    {
        return (new RealisationTacheService)
            ->getRevisionsNecessairesBeforePriority(realisationTacheId: $this->id);
    }




    /**
     * Retourne l'ID de l'évaluateur (ou formateur évaluateur) connecté, ou null.
     */
    public function currentEvaluateurId(): ?int
    {
        $user = Auth::user();
        if (! $user) {
            return null;
        }

        // Si le user est évaluateur, retourne son ID d'évaluateur
        if ($user->hasRole('evaluateur')) {
            return $user->evaluateur?->id;
        }

        // Si le user est formateur MAIS qu'il est listé comme évaluateur sur ce projet
        if ($user->hasRole('formateur')) {
            $evaluateurs = $this
                ->realisationProjet?->affectationProjet?->evaluateurs
                ->pluck('id');

            if ($evaluateurs != null && $evaluateurs->contains($user->evaluateur?->id)) {
                return $user->evaluateur->id;
            }
        }

        return null;
    }

    /**
     * Moyenne des notes (correspond à $this->note).
     */
    public function getAverageNote(): float|null
    {
        return $this->note !== null
            ? round($this->note, 2)
            : null;
    }

    /**
     * Note “personnelle” de l’évaluateur connecté :
     * si l'évaluateur a déjà noté, retourne sa note, sinon null.
     */
    public function getPersonalNote(): float|null
    {
        $evalId = $this->currentEvaluateurId();
        if (! $evalId) {
            return null;
        }

        $eval = $this->evaluationRealisationTaches()
                     ->where('evaluateur_id', $evalId)
                     ->first();

        return $eval?->note !== null
            ? round($eval->note, 2)
            : null;
    }

    /**
     * Détermine si l'utilisateur connecté peut éditer la note :
     * - pas d'entité encore sauvegardée
     * - ou rôle formateur ou évaluateur
     */
    public function canEditNote(): bool
    {
        $user = Auth::user();
        if (! $user) {
            return false;
        }

        if (! $this->id) {
            return true;
        }

        return $user->hasAnyRole(['formateur', 'evaluateur']);
    }


    /**
     * Note à afficher :
     * - si des évaluateurs existent
     *     • retourne la note perso si l’évaluateur a déjà noté,
     *     • sinon retourne null (pas encore d’évaluation)
     * - si aucun évaluateur défini
     *     • retourne la moyenne de toutes les évaluations (champ note)
     *
     * @return float|null
     */
    public function getDisplayNote(): ?float
    {
        // IDs des évaluateurs du projet
        $evaluateurs = $this
            ->realisationProjet?->affectationProjet?->evaluateurs?->pluck('id');

        // S’il y a des évaluateurs
        if ($evaluateurs != null && $evaluateurs->isNotEmpty()) {
            // Tenter de récupérer la note perso
            if ($personal = $this->getPersonalNote()) {
                return $personal;
            }
            // Pas de note perso : on renvoie null
            return null;
        }

        // Pas d’évaluateurs : on tombe sur la moyenne stockée
        return $this->getAverageNote();
    }


    /**
     * Barème max pour la note = note de la tâche liée.
     */
    public function getMaxNote(): float|null
    {
        return $this->tache?->note !== null
            ? round($this->tache->note, 2)
            : null;
    }


    /**
     * Retourne le message à afficher :
     * - message personnel de l’évaluateur/formateur assigné si existant,
     * - sinon la remarque du formateur,
     * - ou chaîne vide si des évaluateurs existent et aucun message perso.
     *
     * @return string|null
     */
    public function getDisplayMessage(): ?string
    {
        // Liste des évaluateurs de ce projet
        $evaluateurs = $this
            ->realisationProjet?->affectationProjet?->evaluateurs
            ->pluck('id');

        // Si des évaluateurs existent, on préfère le message perso ou vide
        if ($evaluateurs != null && $evaluateurs->isNotEmpty()) {
            // Si un message perso existe, on le retourne
            if ($msg = $this->getPersonalMessage()) {
                return $msg;
            }

            // Aucun message perso, on renvoie une chaîne vide
            return '';
        }

        // Si pas d’évaluateurs, on tombe sur la remarque du formateur
        return $this->remarque_evaluateur;
    }

    /**
     * Retourne le message personnel de l’évaluateur ou formateur assigné, ou null s’il n’existe pas.
     *
     * @return string|null
     */
    public function getPersonalMessage(): ?string
    {
        $evalId = $this->currentEvaluateurId();
        if (! $evalId) {
            return null;
        }

        $eval = $this->evaluationRealisationTaches()
                    ->where('evaluateur_id', $evalId)
                    ->first();

        return $eval?->message;
    }


}
