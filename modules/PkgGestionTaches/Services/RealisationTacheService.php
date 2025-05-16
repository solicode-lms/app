<?php

namespace Modules\PkgGestionTaches\Services;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgGestionTaches\Models\RealisationTache;
use Modules\PkgGestionTaches\Models\Tache;
use Modules\PkgGestionTaches\Models\WorkflowTache;
use Modules\PkgGestionTaches\Services\Base\BaseRealisationTacheService;
use Modules\PkgGestionTaches\Services\RealisationTacheService\RealisationTacheServiceCrud;
use Modules\PkgGestionTaches\Services\RealisationTacheService\RealisationTacheServiceWidgets;
use Modules\PkgGestionTaches\Services\RealisationTacheService\RealisationTacheWorkflow;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 */
class RealisationTacheService extends BaseRealisationTacheService
{
    use 
        RealisationTacheServiceCrud,
        RealisationTacheServiceWidgets,  
        RealisationTacheWorkflow;


    public function initFieldsFilterable()
    {

        $scopeVariables = $this->viewState->getScopeVariables('realisationTache');
        $this->fieldsFilterable = [];
        $sessionState = $this->sessionState;

        // Groupe 
        if(!Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE,Role::APPRENANT_ROLE) || !empty($this->viewState->get("filter.realisationTache.RealisationProjet.AffectationProjet.Groupe_id") ) ) {
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
             "[name='tache_id']",
            route('taches.getData'),
             "projet.affectationProjets.id"
        );
       
        // Etat - Solicode
        // If formateur ou apprenant
        if(Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE,Role::APPRENANT_ROLE)){
            // Affichage des état de formateur
            // Etat
            $etatRealisationTacheService = new EtatRealisationTacheService();
            $etatRealisationTaches = match (true) {
                Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $etatRealisationTacheService->getEtatRealisationTacheByFormateurId($sessionState->get("formateur_id")),
                Auth::user()->hasRole(Role::APPRENANT_ROLE) => $etatRealisationTacheService->getEtatRealisationTacheByFormateurDApprenantId($sessionState->get("apprenant_id")),
                default => $etatRealisationTacheService->all(),
            };
            $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                __("PkgGestionTaches::etatRealisationTache.plural"), 
                'etat_realisation_tache_id', 
                \Modules\PkgGestionTaches\Models\EtatRealisationTache::class, 
                'nom',
                $etatRealisationTaches);
        }
        
      
        if(!Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE,Role::APPRENANT_ROLE) || !empty($this->viewState->get("filter.realisationTache.etatRealisationTache.WorkflowTache.Code") ) ) {
            // Affichage de l'état de solicode
            $workflowTacheService = new WorkflowTacheService();
            $workflowTaches = $workflowTacheService->all();
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgGestionTaches::workflowTache.plural"), 
                'etatRealisationTache.WorkflowTache.Code', 
                WorkflowTache::class, 
                "code",
                "code",
                $workflowTaches
            );
        }
        



       

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
