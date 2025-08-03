<?php

namespace Modules\PkgRealisationTache\Services\RealisationTacheService;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgAutorisation\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Modules\PkgRealisationTache\Database\Seeders\EtatRealisationTacheSeeder;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Illuminate\Database\Eloquent\Builder;
use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Modules\PkgCompetences\Services\ChapitreService;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Services\HistoriqueRealisationTacheService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService;

trait RealisationTacheServiceCrud
{

    /**
     * Méthode contient les règles métier qui sont appliquer avant l'édition
     * il est utilisée avec tous les méthode qui font update
     * @param mixed $entity
     * @param array $data
     * @return void
     */
    public function beforeUpdateRules(array &$data, $id){
        
        $entity = $this->find($id);


        // ❌ Bloquer l'état si la tâche a des livrables mais aucun n'est encore déposé
        // Il test si $etat est null
        // Il ne l'applique pas au formateur
        if (
            !Auth::user()->hasRole(Role::FORMATEUR_ROLE) &&
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
            // ->with(['realisationProjet.affectationProjet']) // Charger affectationProjet
            ->join('realisation_projets', 'realisation_taches.realisation_projet_id', '=', 'realisation_projets.id')
            ->join('affectation_projets', 'realisation_projets.affectation_projet_id', '=', 'affectation_projets.id')
            ->join('taches', 'realisation_taches.tache_id', '=', 'taches.id')
            ->orderBy('affectation_projets.date_fin', 'desc') // 1️⃣ Trier par date de fin de l'affectation
            ->orderBy('taches.ordre', 'asc') // 2️⃣ Ensuite par ordre de tâche
            ->select('realisation_taches.*'); // 🎯 Important pour éviter le problème de Model::hydrate
    }



   

    /**
     * @param  RealisationTache  $entity
     * @return void
     */
    public function afterUpdateRules(RealisationTache $entity): void
    {
        // 1. Vérifier que l'état de la tâche a bien changé
        if ($entity->wasChanged('etat_realisation_tache_id')) {
            
            // 2. Trouver les chapitres liés à cette tâche
            $chapitres = RealisationChapitre::where('realisation_tache_id', $entity->id)->get();

            if ($chapitres->isNotEmpty()) {
                // 3. Récupérer l'état de chapitre correspondant à l'état de la tâche
                $etatChapitre = $this->mapEtatTacheToEtatChapitre($entity->etat_realisation_tache_id);

                if ($etatChapitre) {
                    // 4. Mettre à jour tous les chapitres liés
                    $realisationChapitreService = new RealisationChapitreService();
                    foreach ($chapitres as $chapitre) {
                        $realisationChapitreService->update($chapitre->id , [
                            'etat_realisation_chapitre_id' => $etatChapitre->id
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Mapper un état de tâche à un état de chapitre
     */
    private function mapEtatTacheToEtatChapitre(int $etatTacheId)
    {
        $etatTache = EtatRealisationTache::with('workflowTache')->find($etatTacheId);

        if (!$etatTache || !$etatTache->workflowTache) {
            return null;
        }

        // Table de mapping entre les codes
        $mapping = [
            'TODO'            => 'TODO',
            'IN_PROGRESS'           => 'IN_PROGRESS',
            'PAUSED'           => 'PAUSED',
            'REVISION_NECESSAIRE'=> 'IN_PROGRESS',
            'READY_FOR_LIVE_CODING' => 'READY_FOR_LIVE_CODING',
            'IN_LIVE_CODING' => 'IN_LIVE_CODING',
            'TO_APPROVE'      => 'TO_APPROVE',
            'DONE'           => 'DONE',
            'BLOCKED' => 'BLOCKED'
        ];

        $codeChapitre = $mapping[$etatTache->workflowTache->code] ?? null;

        if (!$codeChapitre) {
            return null;
        }

        return EtatRealisationChapitre::where('code', $codeChapitre)->first();
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