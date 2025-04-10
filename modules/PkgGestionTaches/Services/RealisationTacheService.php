<?php

namespace Modules\PkgGestionTaches\Services;


use Illuminate\Support\Facades\Auth;

use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgGestionTaches\Models\Tache;
use Modules\PkgGestionTaches\Models\WorkflowTache;
use Modules\PkgGestionTaches\Services\Base\BaseRealisationTacheService;
use Modules\PkgGestionTaches\Services\RealisationTacheService\RealisationTacheServiceCrud;
use Modules\PkgGestionTaches\Services\RealisationTacheService\RealisationTacheServiceWidgets;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 */
class RealisationTacheService extends BaseRealisationTacheService
{
    use RealisationTacheServiceWidgets,  RealisationTacheServiceCrud;


    



    // TODO : Gapp : ajouter un metaData filterDataSource : indique la méthode à utiliser pour trouver data
    // il faut indiquer aussi, plusieurs méthode si les données sont par rôle : 
    // Exemple : formateur, apprenant, all
    public function initFieldsFilterable()
    {


        $scopeVariables = $this->viewState->getScopeVariables('realisationTache');
        $this->fieldsFilterable = [];
        $sessionState = $this->sessionState;

        // TODO Gapp :  ajouter les params de DynamicDromdonw depuis metaData DynamicDropdonw
        // AffectationProjet
        $affectationProjetService = new AffectationProjetService();
        $affectationProjets = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $affectationProjetService->getAffectationProjetsByFormateurId($sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => $affectationProjetService->getAffectationProjetsByApprenantId($sessionState->get("apprenant_id")),
            default => AffectationProjet::all(),
        };
        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgRealisationProjets::affectationProjet.plural"), 
            'realisationProjet.affectation_projet_id', 
            AffectationProjet::class, 
            "id",
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
        }else{
            // Affichage de l'état de solicode
            $workflowTacheService = new WorkflowTacheService();
            $workflowTaches = $workflowTacheService->all();
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgGestionTaches::workflowTache.plural"), 
                'EtatRealisationTache.Workflow_tache_id', 
                WorkflowTache::class, 
                "id",
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
            'realisationProjet.apprenant_id', 
            \Modules\PkgApprenants\Models\Apprenant::class,
            "id",
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





}
