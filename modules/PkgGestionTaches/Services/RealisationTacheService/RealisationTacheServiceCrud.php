<?php

namespace Modules\PkgGestionTaches\Services\RealisationTacheService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgAutorisation\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Modules\PkgGestionTaches\Database\Seeders\EtatRealisationTacheSeeder;
use Modules\PkgGestionTaches\Models\EtatRealisationTache;
use Modules\PkgGestionTaches\Models\RealisationTache;
use Illuminate\Database\Eloquent\Builder;

trait RealisationTacheServiceCrud
{

    public function dataCalcul($realisationTache)
    {
        // En Cas d'édit
        if(isset($realisationTache->id)){
          
        }
      
        return $realisationTache;
    }

    

 



    public function edit(int $id)
    {
        $entity = $this->model->find($id);

        if (is_null($entity->dateDebut)) {
            $entity->dateDebut = now()->toDateString(); // format YYYY-MM-DD sans heure
            $entity->save(); // il faut sauvegarder si tu veux que le changement soit persisté
        }

        return $entity;
    }

     /**
 * Paginer les réalisations de tâches en les triant par la priorité de la tâche associée,
 * tout en incluant celles qui n'ont pas de priorité.
 *
 * @param array $params
 * @param int $perPage
 * @param array $columns
 * @return LengthAwarePaginator
 */
public function paginate(array $params = [], int $perPage = 0, array $columns = ['*']): LengthAwarePaginator
{
    $perPage = $perPage ?: $this->paginationLimit;

    return $this->model::withScope(function () use ($params, $perPage, $columns) {
        $query = $this->allQuery($params);

        // Joindre les tables Tache et PrioriteTache avec LEFT JOIN pour inclure les tâches sans priorité
        $query->leftJoin('taches', 'realisation_taches.tache_id', '=', 'taches.id')
              ->leftJoin('priorite_taches', 'taches.priorite_tache_id', '=', 'priorite_taches.id')
              ->orderByRaw('COALESCE(priorite_taches.ordre, 9999) ASC') // Trier par priorité (les NULL en dernier)
              ->select('realisation_taches.*'); // Sélectionner les colonnes de la table principale

        // Calcul du nombre total des résultats filtrés
        $this->totalFilteredCount = $query->count();

        return $query->paginate($perPage, $columns);
    });
}



public function update($id, array $data)
{
    $record = $this->find($id);

    if (!empty($data["etat_realisation_tache_id"])) {
        $etat_realisation_tache_id = $data["etat_realisation_tache_id"];
        $nouvelEtat = EtatRealisationTache::find($etat_realisation_tache_id);

        // Vérifier si le nouvel état existe
        if ($nouvelEtat) {
            // Empêcher un apprenant d'affecter un état réservé aux formateurs
            if ($nouvelEtat->is_editable_only_by_formateur && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
                throw ValidationException::withMessages([
                    'etat_realisation_tache_id' => "Seul un formateur peut affecter cet état de tâche."
                ]);
            }

            // ✅ Vérifie le respect de la priorité selon le workflow
            $workflowCode = optional($nouvelEtat->workflowTache)->code;
            if ($this->workflowExigeRespectDesPriorites($workflowCode)) {
                $this->verifierTachesMoinsPrioritairesTerminees($record,$workflowCode);
            }
        }

        // Vérification si l'état actuel existe et est modifiable uniquement par un formateur
        if ($record->etatRealisationTache) {
            if (
                $record->etatRealisationTache->is_editable_only_by_formateur
                && $record->etatRealisationTache->id != $etat_realisation_tache_id
                && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)
            ) {
                throw ValidationException::withMessages([
                    'etat_realisation_tache_id' => "Cet état de projet doit être modifié par le formateur."
                ]);
            }
        }
    }

    // Mise à jour standard du projet
    return parent::update($id, $data);
}


protected function workflowExigeRespectDesPriorites(?string $workflowCode): bool
{
    if (!$workflowCode) {
        return false;
    }

    // Liste des codes de workflows imposant une validation de priorité
    $workflowsBloquants = [
        'EN_COURS', // adapte selon tes besoins
        'EN_VALIDATION',
        'TERMINEE'
    ];

    return in_array($workflowCode, $workflowsBloquants);
}

protected function verifierTachesMoinsPrioritairesTerminees(RealisationTache $realisationTache,$workflowCode): void
{
    // Charger les relations nécessaires
    $realisationTache->loadMissing('etatRealisationTache.workflowTache', 'tache.prioriteTache');

 
    // Appliquer la règle seulement si le workflow le demande
    if (!$this->workflowExigeRespectDesPriorites($workflowCode)) {
        return;
    }

    $realisationProjetId = $realisationTache->realisation_projet_id;
    $tache = $realisationTache->tache;

    if ($tache && $tache->prioriteTache) {
        $ordreActuel = $tache->prioriteTache->ordre;

        // Les états considérés comme "terminés" ou non bloquants
        $etatsFinaux = ['TERMINEE', 'EN_VALIDATION'];

        $tachesBloquantes = RealisationTache::where('realisation_projet_id', $realisationProjetId)
            ->whereHas('tache.prioriteTache', function ($query) use ($ordreActuel) {
                $query->where('ordre', '<', $ordreActuel);
            })
            ->where(function ($query) use ($etatsFinaux) {
                $query
                    ->whereHas('etatRealisationTache.workflowTache', function ($q) use ($etatsFinaux) {
                        $q->whereNotIn('code', $etatsFinaux);
                    })
                    ->orDoesntHave('etatRealisationTache');
            })
            ->with('tache') // Charger les noms des tâches
            ->get();

    

        if ($tachesBloquantes->isNotEmpty()) {
            $nomsTaches = $tachesBloquantes->pluck('tache.titre')->filter()->map(function ($nom) {
                return "<li>" . e($nom) . "</li>";
            })->join('');

            $message = "<p> Impossible de passer à cet état : les tâches plus prioritaires  <br> suivantes ne sont pas encore terminées</p><ul>$nomsTaches</ul>";

            throw ValidationException::withMessages([
                'etat_realisation_tache_id' => $message
            ]);
        }
    }
}


}