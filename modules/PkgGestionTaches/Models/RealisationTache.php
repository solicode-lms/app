<?php
 

namespace Modules\PkgGestionTaches\Models;

use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Models\Livrable;
use Modules\PkgGestionTaches\Models\Base\BaseRealisationTache;
use Modules\PkgGestionTaches\Services\RealisationTacheService;
use Modules\PkgRealisationProjets\Models\LivrablesRealisation;

class RealisationTache extends BaseRealisationTache
{
   
   /**
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

    public function __toString()
    {
        return ($this->tache?->titre ?? "") .  " - ". $this->realisationProjet?->apprenant ?? "";
    }

    public function getRevisionsBeforePriority(): \Illuminate\Database\Eloquent\Collection
    {
        return (new RealisationTacheService)
            ->getRevisionsNecessairesBeforePriority($this->id);
    }



     /**
     * Retourne l'ID de l'évaluateur connecté (ou null).
     */
    protected function currentEvaluateurId(): ?int
    {
        $user = Auth::user();
        return $user && $user->hasRole('evaluateur')
            ? $user->evaluateur?->id
            : null;
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
     * Valeur à afficher en priorité dans le champ :
     * note perso si existe, sinon moyenne.
     */
    public function getDisplayNote(): float|null
    {
        return $this->getPersonalNote() ?? $this->getAverageNote();
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
}
