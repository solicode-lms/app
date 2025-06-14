<?php

namespace Modules\PkgGestionTaches\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgGestionTaches\Models\EtatRealisationTache;
use Modules\PkgGestionTaches\Models\RealisationTache;
use Modules\PkgGestionTaches\Models\Tache;
use Modules\PkgGestionTaches\Models\WorkflowTache;
use Modules\PkgGestionTaches\Services\Base\BaseRealisationTacheService;
use Modules\PkgGestionTaches\Services\RealisationTacheService\RealisationTacheServiceCrud;
use Modules\PkgGestionTaches\Services\RealisationTacheService\RealisationTacheServiceWidgets;
use Modules\PkgGestionTaches\Services\RealisationTacheService\RealisationTacheWorkflow;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgValidationProjets\Services\EvaluationRealisationTacheService;
use Illuminate\Support\Collection;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 */
class RealisationTacheService extends BaseRealisationTacheService
{
    use 
        RealisationTacheServiceCrud,
        RealisationTacheServiceWidgets,  
        RealisationTacheWorkflow;

        protected array $query_all_with_relations = [
            'tache',
            'tache.projet',
            'tache.projet.filiere',
            'tache.projet.formateur',
            'tache.projet.resources',
            'tache.projet.livrables',
            'realisationProjet',
            'realisationProjet.apprenant',
            'etatRealisationTache.workflowTache'
        ];

    public function initFieldsFilterable()
    {

        $scopeVariables = $this->viewState->getScopeVariables('realisationTache');
        $this->fieldsFilterable = [];
        $sessionState = $this->sessionState;

        // Groupe 
        if(Auth::user()->hasRole(Role::ADMIN_ROLE) || !Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE,Role::APPRENANT_ROLE) || !empty($this->viewState->get("filter.realisationTache.RealisationProjet.AffectationProjet.Groupe_id") ) ) {
            // Affichage de l'état de solicode
            $groupeService = new GroupeService();
            $groupes = $groupeService->all();
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgApprenants::Groupe.plural"), 
                'RealisationProjet.AffectationProjet.Groupe_id', 
                Groupe::class, 
                "code",
                "id",
                $groupes,
                "[name='RealisationProjet.Affectation_projet_id']",
                route('affectationProjets.getData'),
                "groupe_id"
            );
        }

        // AffectationProjet
        $affectationProjetService = new AffectationProjetService();
        $affectationProjets = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $affectationProjetService->getAffectationProjetsByFormateurId($sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => $affectationProjetService->getAffectationProjetsByApprenantId($sessionState->get("apprenant_id")),
            default => AffectationProjet::all(),
        };
        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgRealisationProjets::affectationProjet.plural"), 
            'RealisationProjet.Affectation_projet_id', 
            AffectationProjet::class, 
            "id","id",
            $affectationProjets, 
             "[name='tache_id'],[name='etat_realisation_tache_id']",
            route('taches.getData') . "," . route('etatRealisationTaches.getData'),
             "projet.affectationProjets.id,formateur.projets.affectationProjets.id"
        );
       
        // --- ETAT REALISATION TACHE : choix selon AffectationProjet, Formateur ou Apprenant ---
        $affectationProjetId = $this->viewState->get(
            'filter.realisationTache.RealisationProjet.Affectation_projet_id'
        );

        $affectationProjetId = AffectationProjet::find($affectationProjetId) ? $affectationProjetId : null;
       
        $etatService = new EtatRealisationTacheService();

        if (!empty($affectationProjetId)) {
            // Cas 1 : AffectationProjet sélectionnée
            $affectationProjet = (new AffectationProjetService())->find($affectationProjetId);
            // Afficher les états de formateur pour ce projet
            $etats = $affectationProjet->projet->formateur->etatRealisationTaches;
        }
        // elseif (Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
        //     // Cas 2 : Formateur sans projet sélectionné
        //     // Afficher les états génériques du formateur
        //     $etats = $etatService->getEtatRealisationTacheByFormateurId(
        //        $this->sessionState->get("formateur_id")
        //     );
        // }
        else {
            // Cas 3 : Apprenant ou autre rôle
            // Aucun état formateur -> liste vide pour masquer le filtre
           $etats =  collect();
        }

        // Génération du filtre ManyToOne pour l'état de réalisation de tâche
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __('PkgGestionTaches::etatRealisationTache.plural'),
            'etat_realisation_tache_id',
            EtatRealisationTache::class,
            'nom',
            $etats
        );
      
        // Affiche  WorkflowTache
        // Afficher si le filtre est selectionné
        // ou si le l'affectation de projet n'est pas selectionné et que l'acteur n'est pas formateur
        // dans ce cas il faut afficher WorkflowTache
        // --- WORKFLOW TACHE (état SoliCode) ---
        $workflowFilterCode = $this->viewState->get(
            'filter.realisationTache.etatRealisationTache.WorkflowTache.Code'
        );

      
        $workflowService = new WorkflowTacheService();
        $workflows = $workflowService->all();
    
        // Génération du filtre Relation pour WorkflowTache
        $this->fieldsFilterable[] = $this->generateRelationFilter(
                __('PkgGestionTaches::workflowTache.plural'),
                'etatRealisationTache.WorkflowTache.Code',
                \Modules\PkgGestionTaches\Models\WorkflowTache::class,
                'code',
                'code',
                $workflows
        );
        




        // // --- Affichage conditionnel du filtre état de Solicode (workflowTache) ---
        // if (empty($this->viewState->get("filter.realisationTache.RealisationProjet.Affectation_projet_id"))) {
        //     // Afficher état de solicode (workflowTache) si AffectationProjet NON sélectionné
        //     $workflowTacheService = new WorkflowTacheService();
        //     $workflowTaches = $workflowTacheService->all();
        //     $this->fieldsFilterable[] = $this->generateRelationFilter(
        //         __("PkgGestionTaches::workflowTache.plural"),
        //         'etatRealisationTache.WorkflowTache.Code',
        //         WorkflowTache::class,
        //         "code",
        //         "code",
        //         $workflowTaches
        //     );
        // } else {
        //     // Si AffectationProjet sélectionné, afficher l'état du formateur RESPONSABLE DU PROJET (quel que soit le rôle utilisateur)
        //     $affectationProjetId = $this->viewState->get("filter.realisationTache.RealisationProjet.Affectation_projet_id");
        //     $affectationProjet = \Modules\PkgRealisationProjets\Models\AffectationProjet::find($affectationProjetId);
        //     $formateurId = $affectationProjet?->formateur_id;
        //     if ($formateurId) {
        //         $etatRealisationTacheService = new EtatRealisationTacheService();
        //         $etatRealisationTaches = $etatRealisationTacheService->getEtatRealisationTacheByFormateurId($formateurId);
        //         $this->fieldsFilterable[] = $this->generateManyToOneFilter(
        //             __("PkgGestionTaches::etatRealisationTache.plural"),
        //             'etat_realisation_tache_id',
        //             \Modules\PkgGestionTaches\Models\EtatRealisationTache::class,
        //             'nom',
        //             $etatRealisationTaches
        //         );
        //     }
        // }








       

        // Apprenant
        // TODO : Gapp add MetaData relationFilter
        $apprenants = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => (new FormateurService())->getApprenants($this->sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => (new ApprenantService())->getApprenantsDeGroupe($this->sessionState->get("apprenant_id")),
            default => Apprenant::all(),
        };
        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgApprenants::apprenant.plural"), 
            'RealisationProjet.Apprenant_id', 
            \Modules\PkgApprenants\Models\Apprenant::class,
            "id","id",
            $apprenants);

        // Tâches
        $tacheService = new TacheService();
        $taches = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $tacheService->getTacheByFormateurId($sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => $tacheService->getTacheByApprenantId($sessionState->get("apprenant_id")),
            default => Tache::all(),
        };
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgGestionTaches::tache.plural"),
            'tache_id',
            \Modules\PkgGestionTaches\Models\Tache::class,
            'titre',
            $taches
        );
        

        
    }


    /**
     * Construit la requête pour récupérer les réalisations de tâches
     * en état "REVISION_NECESSAIRE" et priorité inférieure.
     *
     * @param  int  $realisationTacheId
     * @return Builder
     */
   protected function revisionsBeforePriorityQuery(int $realisationTacheId): Builder
    {
        $current = RealisationTache::with('tache.prioriteTache')->findOrFail($realisationTacheId);
        $projectId = $current->realisation_projet_id;
        $priorityOrdre = optional($current->tache->prioriteTache)->ordre;
        if($priorityOrdre == null ) {
            $priorityOrdre  = 0;
        }
        return RealisationTache::query()
            ->where('realisation_projet_id', $projectId)
            ->where('id', '<>', $realisationTacheId)
            ->whereHas('etatRealisationTache.workflowTache', function(Builder $q) {
                $q->where('code', 'REVISION_NECESSAIRE');
            })
            ->whereHas('tache.prioriteTache', function(Builder $q) use ($priorityOrdre) {
                $q->where('ordre', '<', $priorityOrdre);
            });
    }

    /**
     * Compte les réalisations avant priorité.
     *
     * @param  int  $realisationTacheId
     * @return int
     */
    public function countRevisionsNecessairesBeforePriority(int $realisationTacheId): int
    {
        return $this->revisionsBeforePriorityQuery($realisationTacheId)
                    ->count();
    }

    /**
     * Récupère la liste des réalisations avant priorité.
     *
     * @param  int  $realisationTacheId
     * @return Collection<int, RealisationTache>
     */
    public function getRevisionsNecessairesBeforePriority(int $realisationTacheId): Collection
    {
        return $this->revisionsBeforePriorityQuery($realisationTacheId)
                    ->with(['tache', 'etatRealisationTache.workflowTache'])
                    ->get();
    }




}
