<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Services\WorkflowTacheService;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgCreationTache\Services\TacheService;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgCreationProjet\Models\EquipeProjet;
use Modules\PkgRealisationTache\Services\SituationPedagogiqueService;

/**
 * Trait dédié à l'initialisation des filtres TableUI pour RealisationTache.
 * 
 * Isole la configuration dynamique de la barre de filtres (Filière, Groupe, AffectationProjet,
 * EquipeProjet, Situation Pédagogique, EtatRealisationTache, WorkflowTache, Apprenant, Tâche).
 */
trait RealisationTacheFilterTrait
{
    /**
     * Initialise la liste des champs filtrables pour TableUI.
     *
     * @return void
     */
    public function initFieldsFilterable()
    {
        $scopeVariables = $this->viewState->getScopeVariables('realisationTache');
        $this->fieldsFilterable = [];
        $sessionState = $this->sessionState;

        $isAdmin = Auth::user()->hasRole(Role::ADMIN_ROLE) || !Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE, Role::APPRENANT_ROLE);
        $isFormateur = Auth::user()->hasRole(Role::FORMATEUR_ROLE);
        
        $showFiliereFilter = $isAdmin;
        $showGroupeFilter = $isAdmin || !empty($this->viewState->get("filter.realisationTache.RealisationProjet.AffectationProjet.Groupe_id"));

        $formateurGroupes = collect();
        $formateurFilieres = collect();

        if ($isFormateur) {
            $formateur = \Modules\PkgFormation\Models\Formateur::with('groupes')->where('user_id', Auth::id())->first();
            if ($formateur) {
                $formateurGroupes = $formateur->groupes;
                $filieresIds = $formateurGroupes->pluck('filiere_id')->unique();
                $formateurFilieres = \Modules\PkgFormation\Models\Filiere::whereIn('id', $filieresIds)->get();
                
                if ($filieresIds->count() > 1) {
                    $showFiliereFilter = true;
                }
                if ($formateurGroupes->count() > 1) {
                    $showGroupeFilter = true;
                }
            }
        }

        // Filiere
        if ($showFiliereFilter) {
            $filiereService = new \Modules\PkgFormation\Services\FiliereService();
            $filieres = $isFormateur ? $formateurFilieres : $filiereService->all();
            
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgFormation::filiere.plural"),
                'RealisationProjet.AffectationProjet.Groupe.Filiere_id',
                \Modules\PkgFormation\Models\Filiere::class,
                "nom",
                "id",
                $filieres,
                "[name='RealisationProjet.AffectationProjet.Groupe_id']",
                route('groupes.getData'),
                "filiere_id"
            );
        }

        // Groupe 
        if ($showGroupeFilter) {
            $groupeService = new GroupeService();
            $groupesList = $isFormateur ? $formateurGroupes : $groupeService->all();
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgApprenants::groupe.plural"),
                'RealisationProjet.AffectationProjet.Groupe_id',
                Groupe::class,
                "code",
                "id",
                $groupesList,
                "[name='RealisationProjet.Affectation_projet_id'],[name='RealisationProjet.Apprenant_id']",
                route('affectationProjets.getData') . "," . route('apprenants.getData'),
                "groupe_id,groupe_id"
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
            "id",
            "id",
            $affectationProjets,
            "[name='tache_id'],[name='etat_realisation_tache_id'],[name='label_projet_id'],[name='tache.equipe_projet_id']",
            route('taches.getData') . "," . route('etatRealisationTaches.getData') . "," . route('labelProjets.getData') . "," . route('equipeProjets.getData'),
            "projet.affectationProjets.id,formateur.projets.affectationProjets.id,projet.affectationProjets.id,projet.affectationProjets.id"
        );

        // --- FILTRE EQUIPE PROJET ---
        $affectationProjetId = $this->viewState->get(
            'filter.realisationTache.RealisationProjet.Affectation_projet_id'
        );

        $equipeProjets = collect();
        if (!empty($affectationProjetId)) {
            $affectationProjet = $affectationProjetService->find($affectationProjetId);
            $projet = $affectationProjet?->projet;
            if ($projet) {
                $equipeProjets = $projet->equipeProjets;
            }
        }

        $filterEquipe = $this->generateRelationFilter(
            __("PkgCreationProjet::equipeProjet.singular"),
            'tache.equipe_projet_id',
            \Modules\PkgCreationProjet\Models\EquipeProjet::class,
            "nom",
            "id",
            $equipeProjets,
            "[name='tache_id'],[name='RealisationProjet.Apprenant_id']",
            route('taches.getData') . "," . route('apprenants.getData'),
            "equipe_projet_id,equipe_projet_id"
        );
        $filterEquipe['hidden'] = $equipeProjets->isEmpty();
        $this->fieldsFilterable[] = $filterEquipe;

        // --- ETAT REALISATION TACHE : choix selon AffectationProjet, Formateur ou Apprenant ---
        $affectationProjetId = $this->viewState->get(
            'filter.realisationTache.RealisationProjet.Affectation_projet_id'
        );

        $affectationProjetId = AffectationProjet::find($affectationProjetId) ? $affectationProjetId : null;

        $etatService = new EtatRealisationTacheService();

        if (!empty($affectationProjetId)) {
            // Cas 1 : AffectationProjet sélectionnée
            $affectationProjet = (new AffectationProjetService())->find($affectationProjetId);
            $etats = $affectationProjet->projet->formateur->etatRealisationTaches;
        } elseif (Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
            // Cas 2 : Formateur sans projet sélectionné
            $etats = $etatService->getEtatRealisationTacheByFormateurId(
                $this->sessionState->get("formateur_id")
            );
        } else {
            // Cas 3 : Apprenant ou autre rôle
            $etats = collect();
        }

        // --- FILTRE SITUATION PEDAGOGIQUE (Macro-situations & Personnalisée) ---
        $this->fieldsFilterable[] = [
            'label' => __('PkgRealisationTache::realisationTache.situation_pedagogique'),
            'field' => 'situation_pedagogique',
            'type' => 'ManyToOne',
            'options' => app(SituationPedagogiqueService::class)->getSituationFilterOptions(),
        ];

        // Génération du filtre ManyToOne pour l'état de réalisation de tâche
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __('PkgRealisationTache::etatRealisationTache.plural'),
            'etat_realisation_tache_id',
            EtatRealisationTache::class,
            'nom',
            $etats
        );

        // --- WORKFLOW TACHE (état SoliCode) ---
        $workflowService = new WorkflowTacheService();
        $workflows = $workflowService->all();

        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __('PkgRealisationTache::workflowTache.plural'),
            'etatRealisationTache.WorkflowTache.Code',
            WorkflowTache::class,
            'code',
            'code',
            $workflows
        );

        $equipeProjetId = $this->viewState->get('filter.realisationTache.tache.equipe_projet_id')
            ?? $this->viewState->get('filter.realisationTache.equipe_projet_id');

        // Apprenant
        $apprenants = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => (new FormateurService())->getApprenants($this->sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => (new ApprenantService())->getApprenantsDeGroupe($this->sessionState->get("apprenant_id")),
            default => Apprenant::all(),
        };

        if (!empty($equipeProjetId)) {
            $equipe = EquipeProjet::with('apprenants')->find($equipeProjetId);
            if ($equipe) {
                $apprenants = $equipe->apprenants;
            }
        }

        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgApprenants::apprenant.plural"),
            'RealisationProjet.Apprenant_id',
            Apprenant::class,
            "id",
            "id",
            $apprenants
        );

        // Tâches
        $tacheService = new TacheService();
        $taches = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $tacheService->getTacheByFormateurId($sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => $tacheService->getTacheByApprenantId($sessionState->get("apprenant_id")),
            default => Tache::all(),
        };

        if (!empty($equipeProjetId)) {
            $taches = collect($taches)->filter(function($tache) use ($equipeProjetId) {
                return (string) $tache->equipe_projet_id === (string) $equipeProjetId
                    || is_null($tache->equipe_projet_id);
            });
        }
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgCreationTache::tache.plural"),
            'tache_id',
            Tache::class,
            'titre',
            $taches
        );
    }
}
