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
use Modules\PkgRealisationTache\Models\RealisationTache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait RealisationTacheGetterTrait
{
    public function prepareDataForIndexView(array $params = []): array
    {
        // On récupère les données du parent
        $baseData = parent::prepareDataForIndexView($params);

        /** @var \Illuminate\Support\Collection $realisationTaches */
        $realisationTaches = $baseData['realisationTaches_data'];

        // Charger toutes les révisions nécessaires en une seule requête
        $revisionsGrouped = RealisationTache::query()
            ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                $q->where('code', 'REVISION_NECESSAIRE');
            })
            ->whereIn('realisation_projet_id', $realisationTaches->pluck('realisation_projet_id'))
            ->with(['tache', 'etatRealisationTache.workflowTache'])
            ->get()
            ->groupBy('realisation_projet_id');

        // Ajouter dans $baseData
        $baseData['revisionsBeforePriorityGrouped'] = $revisionsGrouped;

        // --- Nouveau : toutes les RT des mêmes projets (pour trouver la précédente par ordre-1)
        $previousTasksGrouped = RealisationTache::query()
            ->whereIn('realisation_projet_id', $realisationTaches->pluck('realisation_projet_id'))
            ->with([
                // on a besoin de l'ordre/priorité pour comparer
                'tache',
                'etatRealisationTache.workflowTache',
            ])
            ->get()
            ->groupBy('realisation_projet_id');

        $baseData['previousTasksGrouped'] = $previousTasksGrouped;


        // Dispo dans Blade via compact_value
        if (isset($baseData['realisationTache_compact_value']) && is_array($baseData['realisationTache_compact_value'])) {
            $baseData['realisationTache_compact_value']['revisionsBeforePriorityGrouped'] = $revisionsGrouped;
            $baseData['realisationTache_compact_value']['previousTasksGrouped'] = $previousTasksGrouped;
        }

        return $baseData;
    }

    /**
     * Summary of initFieldsFilterable
     * @return void
     */
    public function initFieldsFilterable()
    {

        $scopeVariables = $this->viewState->getScopeVariables('realisationTache');
        $this->fieldsFilterable = [];
        $sessionState = $this->sessionState;

        // Groupe 
        if (Auth::user()->hasRole(Role::ADMIN_ROLE) || !Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE, Role::APPRENANT_ROLE) || !empty($this->viewState->get("filter.realisationTache.RealisationProjet.AffectationProjet.Groupe_id"))) {
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
            "id",
            "id",
            $affectationProjets,
            "[name='tache_id'],[name='etat_realisation_tache_id'],[name='label_projet_id']",
            route('taches.getData') . "," . route('etatRealisationTaches.getData') . "," . route('labelProjets.getData'),
            "projet.affectationProjets.id,formateur.projets.affectationProjets.id,projet.affectationProjets.id"
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
        } elseif (Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
            // Cas 2 : Formateur sans projet sélectionné
            // Afficher les états génériques du formateur
            $etats = $etatService->getEtatRealisationTacheByFormateurId(
                $this->sessionState->get("formateur_id")
            );
        } else {
            // Cas 3 : Apprenant ou autre rôle
            // Aucun état formateur -> liste vide pour masquer le filtre
            $etats = collect();
        }

        // Génération du filtre ManyToOne pour l'état de réalisation de tâche
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __('PkgRealisationTache::etatRealisationTache.plural'),
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
            __('PkgRealisationTache::workflowTache.plural'),
            'etatRealisationTache.WorkflowTache.Code',
            WorkflowTache::class,
            'code',
            'code',
            $workflows
        );


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
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgCreationTache::tache.plural"),
            'tache_id',
            Tache::class,
            'titre',
            $taches
        );



         if (!array_key_exists('labelProjets', $scopeVariables)) {

                    $labelProjetService = new \Modules\PkgCreationProjet\Services\LabelProjetService();
                    $labelProjetIds = $this->getAvailableFilterValues('labelProjets.id');
                    $labelProjets = $labelProjetService->getByIds($labelProjetIds);

                    $this->fieldsFilterable[] = $this->generateManyToManyFilter(
                        __("PkgCreationProjet::labelProjet.plural"), 
                        'label_projet_id', 
                        \Modules\PkgCreationProjet\Models\LabelProjet::class, 
                        'nom',
                        $labelProjets
                    );
            }


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
        $current = RealisationTache::findOrFail($realisationTacheId);
        $projectId = $current->realisation_projet_id;
        $priorityOrdre = $current->tache->priorite;
        if ($priorityOrdre == null) {
            $priorityOrdre = 0;
        }
        return RealisationTache::query()
            ->where('realisation_projet_id', $projectId)
            ->where('id', '<>', $realisationTacheId)
            ->whereHas('etatRealisationTache.workflowTache', function (Builder $q) {
                $q->where('code', 'REVISION_NECESSAIRE');
            })
            ->whereHas('tache', function (Builder $q) use ($priorityOrdre) {
                $q->where('priorite', '<', $priorityOrdre);
            });
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
     * Vérifie l'existence d'une réalisation de tâche pour une tâche et une réalisation de projet données.
     *
     * @param int $tacheId
     * @param int $realisationProjetId
     * @return bool
     */
    public function existsForTacheAndProject(int $tacheId, int $realisationProjetId): bool
    {
        return $this->allQuery()->where([
            'tache_id' => $tacheId,
            'realisation_projet_id' => $realisationProjetId
        ])->exists();
    }
}
