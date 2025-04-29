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
use Modules\PkgGestionTaches\Models\HistoriqueRealisationTache;
use Modules\PkgGestionTaches\Models\WorkflowTache;

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
        // $query->leftJoin('taches', 'realisation_taches.tache_id', '=', 'taches.id')
        //       ->leftJoin('priorite_taches', 'taches.priorite_tache_id', '=', 'priorite_taches.id')
        //       ->orderByRaw('COALESCE(priorite_taches.ordre, 9999) ASC') // Trier par priorité (les NULL en dernier)
        //       ->select('realisation_taches.*'); // Sélectionner les colonnes de la table principale

        // Calcul du nombre total des résultats filtrés
        $this->totalFilteredCount = $query->count();

        return $query->paginate($perPage, $columns);
    });
}



// public function update($id, array $data)
// {
//     $record = $this->find($id);

//     // Empêcher un apprenant d'affecter un état réservé aux formateurs
//     $this->update_bl($record,$data);
//     // Mise à jour standard du projet
//     return parent::update($id, $data);
// }

public function update_bl($record, array &$data){


        $this->enregistrerChangement($record,$data);

        $this->mettreAJourEtatRevisionSiRemarqueModifiee($record, $data);

 
        // 🛡️ Si l'utilisateur  est  formateur, on sort sans rien faire
        if (Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
            return;
        }
        

       // Empêcher un apprenant d'affecter un état réservé aux formateurs
       if (!empty($data["etat_realisation_tache_id"])) {
        $etat_realisation_tache_id = $data["etat_realisation_tache_id"];
        $nouvelEtat = EtatRealisationTache::find($etat_realisation_tache_id);

        // Vérifier si le nouvel état existe
        if ($nouvelEtat) {
          
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

}

// public function updateOnlyExistanteAttribute($id, array $data)
// {
//     $record = $this->find($id);

//     $this->update_bl($record,$data);

//     // Mise à jour standard du projet
//     return parent::update($id, $data);
// }

 


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




    public function insererHistoriqueFeedback(RealisationTache $realisationTache, string $changement): HistoriqueRealisationTache
    {
        return HistoriqueRealisationTache::create([
            'realisation_tache_id' => $realisationTache->id,
            'dateModification' => now(),
            'changement' => $changement,
        ]);
    }

protected function enregistrerChangement(RealisationTache $realisationTache, array $nouveauxChamps)
{
    $champsModifies = [];

    foreach ($nouveauxChamps as $champ => $nouvelleValeur) {
        $ancienneValeur = $realisationTache->$champ ?? null;

        // 🔍 Si l'ancien OU le nouveau est une date / datetime, on formate avant comparaison
        if ($this->estDateOuDateTime($ancienneValeur) || $this->estDateOuDateTime($nouvelleValeur)) {
            $ancienneFormatee = $this->formatterDate($ancienneValeur);
            $nouvelleFormatee = $this->formatterDate($nouvelleValeur);

            if ($ancienneFormatee !== $nouvelleFormatee) {
                $champsModifies[$champ] = $nouvelleValeur;
            }
        } else {
            // Cas normal
            if ($ancienneValeur != $nouvelleValeur) {
                $champsModifies[$champ] = $nouvelleValeur;
            }
        }
    }

    if (!empty($champsModifies)) {
        $changement = collect($champsModifies)
            ->map(function ($value, $key) use ($realisationTache) {
                $label = ucfirst(__("PkgGestionTaches::realisationTache.$key")); // 💬 traduction via lang('fields.nom_champ')

                // 🛠️ Vérifier si c'est une relation ManyToOne
                // 🛠️ Est-ce que ce champ est une clé étrangère ManyToOne ?
                if (isset($realisationTache->manyToOne)) {
                    foreach ($realisationTache->manyToOne as $relationName => $relationData) {
                        if (array_key_exists('foreign_key', $relationData) && $relationData['foreign_key'] === $key) {
                            // Charger la nouvelle entité par son ID
                            $modelClass = $relationData['model'];
                            $nouvelObjet = $modelClass::find($value);
                            if ($nouvelObjet) {
                                return "$label : " . $nouvelObjet->__toString();
                            }
                        }
                    }
                }




                return "$label : " . (is_scalar($value) ? $value : json_encode($value));
            })
            ->implode(' </br> ');

        $this->insererHistoriqueFeedback($realisationTache, $changement);
    }
}
    

    /**
 * Vérifie si la valeur est une date ou datetime.
 */
protected function estDateOuDateTime($valeur): bool
{
    return $valeur instanceof \DateTimeInterface || (is_string($valeur) && strtotime($valeur) !== false);
}

/**
 * Formate la date en string standard pour comparaison.
 */
protected function formatterDate($valeur): ?string
{
    if ($valeur instanceof \DateTimeInterface) {
        return $valeur->format('Y-m-d H:i:s');
    }

    if (is_string($valeur) && strtotime($valeur) !== false) {
        return date('Y-m-d H:i:s', strtotime($valeur));
    }

    return null;
}

/**
 * Devine la méthode relation du modèle.
 */
protected function getRelationMethodName(string $relationName): string
{
    // Convention Laravel : méthode en camelCase
    return lcfirst($relationName);
}



    /**
     * Met à jour automatiquement l'état de la tâche en "Révision nécessaire"
     * si l'attribut `remarques_formateur` est modifié par un formateur.
     *
     * - Si l'état "REVISION_NECESSAIRE" n'existe pas pour ce formateur,
     *   il est automatiquement créé à partir du workflow correspondant.
     * - Si l'état actuel est déjà "REVISION_NECESSAIRE", aucun changement n’est effectué.
     *
     * @param RealisationTache $record L'enregistrement de la réalisation de tâche concerné.
     * @param array $data Les nouvelles données soumises contenant possiblement `remarques_formateur`.
     *
     * @return void
     */
public function mettreAJourEtatRevisionSiRemarqueModifiee(RealisationTache $record, array &$data)
{

      // 🛡️ Si l'utilisateur  n'est pas  formateur, on sort sans rien faire
      if (!Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
        return;
    }

    // Vérifier si la remarque formateur a changé
    if (!array_key_exists('remarques_formateur', $data)) {
        return;
    }

    $ancienneRemarque = $record->remarques_formateur;
    $nouvelleRemarque = $data['remarques_formateur'];

    if ($ancienneRemarque === $nouvelleRemarque) {
        return;
    }

    // Vérifier l'état actuel
    $etatActuel = $record->etatRealisationTache;
    if ($etatActuel && $etatActuel->reference === 'REVISION_NECESSAIRE') {
        return; // Déjà en "Révision nécessaire"
    }

    // Chercher ou créer l'état REVISION_NECESSAIRE pour le formateur connecté
    $wk_revision_necessaire = $this->getWorkflowRevision();

    $etatRevision = EtatRealisationTache::firstOrCreate([
        'workflow_tache_id' => $wk_revision_necessaire->id ,
        'formateur_id' => Auth::user()->formateur->id ?? null,
    ], [
        'nom' => $wk_revision_necessaire->titre,
        'description' => $wk_revision_necessaire->description,
        'is_editable_only_by_formateur' => false,
        'sys_color_id' => $wk_revision_necessaire->sys_color_id, // Choisir une couleur par défaut appropriée
        'workflow_tache_id' => $wk_revision_necessaire->id,
    ]);

    // La modifcation sera efectuer par update
    $data["etat_realisation_tache_id"] = $etatRevision->id;
    
    
}

protected function getWorkflowRevision()
{
    return WorkflowTache::firstOrCreate([
        'code' => 'REVISION_NECESSAIRE'
    ], [
        'titre' => 'Révision nécessaire',
        'description' => 'La tâche a été révisée par le formateur.',
        'sys_color_id' => 4, // Couleur neutre
        'reference' => 'REVISION_NECESSAIRE',
    ]);
}
}