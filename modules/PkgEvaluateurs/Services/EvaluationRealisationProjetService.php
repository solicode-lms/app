<?php


namespace Modules\PkgEvaluateurs\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgEvaluateurs\Models\EtatEvaluationProjet;
use Modules\PkgEvaluateurs\Models\EvaluationRealisationProjet;
use Modules\PkgEvaluateurs\Services\Base\BaseEvaluationRealisationProjetService;

/**
 * Classe EvaluationRealisationProjetService pour gérer la persistance de l'entité EvaluationRealisationProjet.
 */
class EvaluationRealisationProjetService extends BaseEvaluationRealisationProjetService
{
   protected array $index_with_relations = [
        'realisationProjet',
        'realisationProjet.apprenant',
        'realisationProjet.affectationProjet',
        'evaluateur',
        'etatEvaluationProjet',
    ];

    public function initFieldsFilterable()
    {


         // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('evaluationRealisationProjet');
        $this->fieldsFilterable = [];
        $sessionState = $this->sessionState;

      


        // Groupe 
        if(Auth::user()->hasRole(Role::ADMIN_ROLE) || !Auth::user()->hasAnyRole(Role::EVALUATEUR_ROLE) || !empty($this->viewState->get("filter.evaluationRealisationProjet.RealisationProjet.AffectationProjet.Groupe_id") ) ) {
            // Affichage de l'état de solicode
            $groupeService = new GroupeService();
            $groupes = $groupeService->getGroupesAvecAffectationProjetEvaluateurs();
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgApprenants::Groupe.plural"), 
                'RealisationProjet.AffectationProjet.Groupe_id', 
                Groupe::class, 
                "code",
                "id",
                $groupes,
                "[name='RealisationProjet.Affectation_projet_id']",
                route('affectationProjets.getDataHasEvaluateurs'),
                "groupe_id"
            );
        }

        // AffectationProjet
        $affectationProjetService = new AffectationProjetService();
        $affectationProjets = match (true) {
            Auth::user()->hasRole(Role::EVALUATEUR_ROLE) => $affectationProjetService->getAffectationProjetsByEvaluateurId($sessionState->get("evaluateur_id")),
            default =>  $affectationProjetService->getAffectationProjetsAvecEvaluateurs(),
        };
        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgRealisationProjets::affectationProjet.plural"), 
            'RealisationProjet.Affectation_projet_id', 
            AffectationProjet::class, 
            "id","id",
            $affectationProjets, 
            "[name='RealisationProjet.Apprenant_id'],[name='etat_realisation_tache_id']",
            route('apprenants.getData') . "," . route('etatRealisationTaches.getData'),
            "groupes.affectationProjets.id,formateur.projets.affectationProjets.id"
        );


        // Apprenant
        $apprenants = match (true) {
            Auth::user()->hasRole(Role::EVALUATEUR_ROLE) => (new ApprenantService())->getApprenantsHasEvaluationRealisationProjetByEvaluateur($this->sessionState->get("evaluateur_id")),
            default => Apprenant::all(),
        };

        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgApprenants::apprenant.plural"), 
            'RealisationProjet.Apprenant_id', 
            \Modules\PkgApprenants\Models\Apprenant::class,
            "id","id",
            $apprenants);

       
       
        // // --- ETAT REALISATION TACHE : choix selon AffectationProjet, Formateur ou Apprenant ---
        // $affectationProjetId = $this->viewState->get(
        //     'filter.realisationTache.RealisationProjet.Affectation_projet_id'
        // );

        // $affectationProjetId = AffectationProjet::find($affectationProjetId) ? $affectationProjetId : null;
       
        // $etatService = new EtatRealisationTacheService();

        // if (!empty($affectationProjetId)) {
        //     // Cas 1 : AffectationProjet sélectionnée
        //     $affectationProjet = (new AffectationProjetService())->find($affectationProjetId);
        //     // Afficher les états de formateur pour ce projet
        //     $etats = $affectationProjet->projet->formateur->etatRealisationTaches;
        // }
        // // elseif (Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
        // //     // Cas 2 : Formateur sans projet sélectionné
        // //     // Afficher les états génériques du formateur
        // //     $etats = $etatService->getEtatRealisationTacheByFormateurId(
        // //        $this->sessionState->get("formateur_id")
        // //     );
        // // }
        // else {
        //     // Cas 3 : Apprenant ou autre rôle
        //     // Aucun état formateur -> liste vide pour masquer le filtre
        //    $etats =  collect();
        // }

        // // Génération du filtre ManyToOne pour l'état de réalisation de tâche
        // $this->fieldsFilterable[] = $this->generateManyToOneFilter(
        //     __('PkgRealisationTache::etatRealisationTache.plural'),
        //     'etat_realisation_tache_id',
        //     EtatRealisationTache::class,
        //     'nom',
        //     $etats
        // );
      
        // // Affiche  WorkflowTache
        // // Afficher si le filtre est selectionné
        // // ou si le l'affectation de projet n'est pas selectionné et que l'acteur n'est pas formateur
        // // dans ce cas il faut afficher WorkflowTache
        // // --- WORKFLOW TACHE (état SoliCode) ---
        // $workflowFilterCode = $this->viewState->get(
        //     'filter.realisationTache.etatRealisationTache.WorkflowTache.Code'
        // );

      
        // $workflowService = new WorkflowTacheService();
        // $workflows = $workflowService->all();
    
        // // Génération du filtre Relation pour WorkflowTache
        // $this->fieldsFilterable[] = $this->generateRelationFilter(
        //         __('PkgRealisationTache::workflowTache.plural'),
        //         'etatRealisationTache.WorkflowTache.Code',
        //         \Modules\PkgRealisationTache\Models\WorkflowTache::class,
        //         'code',
        //         'code',
        //         $workflows
        // );
        


        

        // // Tâches
        // $tacheService = new TacheService();
        // $taches = match (true) {
        //     Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $tacheService->getTacheByFormateurId($sessionState->get("formateur_id")),
        //     Auth::user()->hasRole(Role::APPRENANT_ROLE) => $tacheService->getTacheByApprenantId($sessionState->get("apprenant_id")),
        //     default => Tache::all(),
        // };
        // $this->fieldsFilterable[] = $this->generateManyToOneFilter(
        //     __("PkgRealisationTache::tache.plural"),
        //     'tache_id',
        //     \Modules\PkgRealisationTache\Models\Tache::class,
        //     'titre',
        //     $taches
        // );
        
    if (!array_key_exists('evaluateur_id', $scopeVariables)) {
            $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgEvaluateurs::evaluateur.plural"), 'evaluateur_id', \Modules\PkgEvaluateurs\Models\Evaluateur::class, 'nom');
            }

            if (!array_key_exists('etat_evaluation_projet_id', $scopeVariables)) {
            $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgEvaluateurs::etatEvaluationProjet.plural"), 'etat_evaluation_projet_id', \Modules\PkgEvaluateurs\Models\EtatEvaluationProjet::class, 'code');
            }

        
    }

    /**
     * Synchronise les enregistrements dans la table `evaluation_realisation_projets`
     * afin qu’il y ait exactement une ligne par (realisation_projet_id, evaluateur_id)
     * pour tous les évaluateurs actuellement affectés à ce projet, sur toutes les réalisations du projet.
     *
     * @param  \Modules\PkgRealisationTache\Models\AffectationProjet  $affectationProjet
     * @return void
     */
    public function SyncEvaluationRealisationProjet($affectationProjet)
    {
        // 1) Récupérer la liste des évaluateurs (leurs IDs) affectés à ce projet
        $evaluateursAssignes = $affectationProjet
            ->evaluateurs()
            ->pluck('id')
            ->toArray();

        // 2) Déterminer l'état initial (le plus petit ordre)
        $defaultEtat = EtatEvaluationProjet::orderBy('ordre')->first();
        $defaultEtatId = $defaultEtat ? $defaultEtat->id : null;

        // 3) Pour chaque RealisationProjet lié à cette AffectationProjet
        $realisationProjets = $affectationProjet->realisationProjets; // relation hasMany
        foreach ($realisationProjets as $realisationProjet) {
            $realisationProjetId = $realisationProjet->id;

            // 4) Récupérer tous les enregistrements existants d’EvaluationRealisationProjet
            $existingRecords = EvaluationRealisationProjet::query()
                ->where('realisation_projet_id', $realisationProjetId)
                ->get(['id', 'evaluateur_id'])
                ->keyBy('evaluateur_id');

            // 5) Synchroniser pour cette réalisation
            DB::transaction(function() use (
                $realisationProjetId,
                $evaluateursAssignes,
                $existingRecords,
                $defaultEtatId
            ) {
                // 5.a) Ajouter les évaluateurs manquants
                foreach ($evaluateursAssignes as $evalId) {
                    if (! isset($existingRecords[$evalId])) {

                        $this->create([
                            'realisation_projet_id'      => $realisationProjetId,
                            'evaluateur_id'              => $evalId,
                            'etat_evaluation_projet_id'  => $defaultEtatId,
                            'date_evaluation' => now()
                        ]);
                    }
                }

                // 5.b) Supprimer les évaluateurs retirés
                $evaluateursExistants = $existingRecords->keys()->toArray();
                $toDelete = array_diff($evaluateursExistants, $evaluateursAssignes);
                if (! empty($toDelete)) {
                    EvaluationRealisationProjet::query()
                        ->where('realisation_projet_id', $realisationProjetId)
                        ->whereIn('evaluateur_id', $toDelete)
                        ->delete();
                }
            });
        }
    }


   public function afterCreateRules($evaluationRealisationProjet, $id)
{
    // 1) Récupérer toutes les RealisationTache liées au même project
    $realisationTaches = RealisationTache::where('realisation_projet_id', $evaluationRealisationProjet->realisation_projet_id)
                       ->get();

    // 2) Pour chaque RealisationTache, créer un EvaluationRealisationTache avec note = null
    foreach ($realisationTaches as $tache) {
        (new EvaluationRealisationTacheService)->create([
            'evaluation_realisation_projet_id' => $evaluationRealisationProjet->id,
            'realisation_tache_id'             => $tache->id,
            'evaluateur_id'                    => $evaluationRealisationProjet->evaluateur_id,
            'note'                             => null,
            'message'                          => null,
        ]);
    }
}



    public function dataCalcul($evaluationRealisationProjet)
    {
        // En Cas d'édit
        if(isset($evaluationRealisationProjet->id)){
          
        }
      
        return $evaluationRealisationProjet;
    }
   
}
