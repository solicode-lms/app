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

    // TODO à migrer vers HistoriqueService
    public function insererHistoriqueFeedback(RealisationTache $realisationTache, string $changement): HistoriqueRealisationTache
    {
        return HistoriqueRealisationTache::create([
            'realisation_tache_id' => $realisationTache->id,
            'dateModification' => now(),
            'changement' => $changement,
        ]);
    }

    // TODO à migrer vers HistoriqueService
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
    

    // TODO : ajouter à une classe DateUtil
    /**
     * Vérifie si la valeur est une date ou datetime.
     */
    protected function estDateOuDateTime($valeur): bool
    {
        return $valeur instanceof \DateTimeInterface || (is_string($valeur) && strtotime($valeur) !== false);
    }

    // TODO : ajouter à une classe DateUtil
    /**
     * Formate la date en string standard pour comparaison.
     * 
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

    // TODO : à trouver une classe Util , GappUtil!!
    /**
     * Devine la méthode relation du modèle.
     */
    protected function getRelationMethodName(string $relationName): string
    {
        // Convention Laravel : méthode en camelCase
        return lcfirst($relationName);
    }




 
}