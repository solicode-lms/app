<?php

namespace Modules\PkgGestionTaches\Services\RealisationTacheService;

use Exception;
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
use Modules\PkgGestionTaches\Services\HistoriqueRealisationTacheService;
use Modules\PkgValidationProjets\Services\EvaluationRealisationTacheService;

trait RealisationTacheServiceCrud
{

   

    /**
     * affectation de dataDebut = now()
     * @param int $id
     */
    public function afterEditRules($entity, $id)
    {
        if (is_null($entity->dateDebut)) {
            $entity->dateDebut = now()->toDateString(); // format YYYY-MM-DD sans heure
            $entity->save(); // il faut sauvegarder si tu veux que le changement soit persisté
        }

        // Déja appliquer par parrent
        // $this->markNotificationsAsRead( $entity->id);
    }

  
    

    /**
     * Trie pardéfaut
     * 1️⃣ Trier par date de fin de l'affectation
     * 2️⃣ Ensuite par ordre de tâche
     * @param mixed $query
     */
    public function defaultSort($query)
    {
        return $query
            ->with(['realisationProjet.affectationProjet']) // Charger affectationProjet
            ->join('realisation_projets', 'realisation_taches.realisation_projet_id', '=', 'realisation_projets.id')
            ->join('affectation_projets', 'realisation_projets.affectation_projet_id', '=', 'affectation_projets.id')
            ->join('taches', 'realisation_taches.tache_id', '=', 'taches.id')
            ->orderBy('affectation_projets.date_fin', 'desc') // 1️⃣ Trier par date de fin de l'affectation
            ->orderBy('taches.ordre', 'asc') // 2️⃣ Ensuite par ordre de tâche
            ->select('realisation_taches.*'); // 🎯 Important pour éviter le problème de Model::hydrate
    }



    /**
     * Méthode contient les règles métier qui sont appliquer pendant l'édition
     * il est utilisée avec tous les méthode qui font update
     * @param mixed $entity
     * @param array $data
     * @return void
     */
    public function beforeUpdateRules(array &$data, $id){
        
        $entity = $this->find($id);


        // ❌ Bloquer l'état si la tâche a des livrables mais aucun n'est encore déposé
        // Il test si $etat est null
        if (
            isset($data["etat_realisation_tache_id"]) &&
            ($etat = EtatRealisationTache::find($data["etat_realisation_tache_id"]))
        ) {
            $etatCode = $etat->workflowTache?->code;
            $etatsInterdits = ['EN_COURS', 'EN_VALIDATION', 'TERMINEE'];

            $tache = $entity->tache;

            if ($tache->livrables()->exists()) {
                $livrables = $tache->livrables;
                $idsLivrables = $livrables->pluck('id');

                // Récupère les IDs des livrables déjà déposés
                $idsLivrablesDeposes = $entity->realisationProjet
                    ->livrablesRealisations()
                    ->whereIn('livrable_id', $idsLivrables)
                    ->pluck('livrable_id');

                // Filtre les livrables non encore déposés
                $livrablesManquants = $livrables->filter(function ($livrable) use ($idsLivrablesDeposes) {
                    return !$idsLivrablesDeposes->contains($livrable->id);
                });

                if ($livrablesManquants->isNotEmpty() && in_array($etatCode, $etatsInterdits)) {
                    $nomsLivrables = $livrablesManquants->pluck('titre')->filter()->map(function ($titre) {
                        return "<li>" . e($titre) . "</li>";
                    })->join('');

                    $message = "<p>Impossible de passer à l’état « {$etat->nom} », </br> les livrables suivants sont requis mais non déposés :</p><ul>{$nomsLivrables}</ul>";

                    throw ValidationException::withMessages([
                        'etat_realisation_tache_id' => $message
                    ]);
                }
            }
        }



        // Empêcher un apprenant d'affecter un état réservé aux formateurs
        if (!Auth::user()->hasRole(Role::FORMATEUR_ROLE) && !empty($data["etat_realisation_tache_id"])) {
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
                    $this->verifierTachesMoinsPrioritairesTerminees($entity,$workflowCode);
                }
            }

            // Vérification si l'état actuel existe et est modifiable uniquement par un formateur
            if ($entity->etatRealisationTache) {
                if (
                    $entity->etatRealisationTache->is_editable_only_by_formateur
                    && $entity->etatRealisationTache->id != $etat_realisation_tache_id
                    && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)
                ) {
                    throw ValidationException::withMessages([
                        'etat_realisation_tache_id' => "Cet état de projet doit être modifié par le formateur."
                    ]);
                }
            }
        }

        if(Auth::user()->hasRole(Role::FORMATEUR_ROLE)){
                // Si des évaluateurs existent, s'assurer que l'utilisateur y figure
                $user = Auth::user();
                $entity = $this->find($id);
                // Récupère les évaluateurs assignés au projet
                $evaluateurs = $entity
                    ->realisationProjet
                    ->affectationProjet
                    ->evaluateurs
                    ->pluck('id');

                
                if ($evaluateurs->isNotEmpty() 
                    && $evaluateurs->doesntContain($user->evaluateur->id)
                ) {
                    throw new Exception("Le formateur n'est pas parmi les évaluateurs de ce projet.");
                }
        }
       

    
           // Historique des modification
        $historiqueRealisationTacheService = new HistoriqueRealisationTacheService();
        $historiqueRealisationTacheService->enregistrerChangement($entity,$data);
        $this->mettreAJourEtatRevisionSiRemarqueModifiee($entity, $data);
        

    }


        /**
     * Après la mise à jour d'une RealisationTache,
     * on crée une EvaluationRealisationTache et on recalcule la moyenne
     * *uniquement* si des évaluateurs existent pour le projet.
     * 
     * @param  RealisationTache  $entity
     * @return void
     */
    public function afterUpdateRules(RealisationTache $entity): void
    {
        // Récupère les évaluateurs assignés au projet
        $evaluateurs = $entity
            ->realisationProjet
            ->affectationProjet
            ->evaluateurs
            ->pluck('id');

        // Si aucun évaluateur n'est défini, on ne fait rien (le formateur a déjà mis à jour la note)
        if ($evaluateurs->isEmpty()) {
            return;
        }

       

        $user = Auth::user();

         // Si l'utilisateur n'est pas un evaluateur ( Apprenant) on ne fait rien
        if ( is_null($user->evaluateur )) {
            return;
        }


        $evaluateurId = $user->evaluateur->id;

         // Crée ou met à jour la note de l'évaluateur sur cette tâche
        (new EvaluationRealisationTacheService())->updateOrCreate(
            ['realisation_tache_id' => $entity->id, 'evaluateur_id' => $evaluateurId],
            ['note' => $entity->note, 'message' => $entity->remarque_evaluateur]
        );

        // Recalcule et met à jour la moyenne
        $moyenne = $entity
            ->evaluationRealisationTaches()
            ->avg('note');

        $entity->update(['note' => round($moyenne, 2)]);
    }

     /**
     * Méthode utilisé pendant le calcule dynamique des champs pendant la l'édition et la création
     * si le champs a le data : data-calcule
     * @param mixed $realisationTache
     */
    public function dataCalcul($realisationTache)
    {
        // En Cas d'édit
        if(isset($realisationTache->id)){
          
        }
      
        return $realisationTache;
    }

}