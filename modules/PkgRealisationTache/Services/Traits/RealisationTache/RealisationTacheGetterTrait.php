<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Modules\PkgRealisationTache\Services\WorkflowTacheService;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgCreationProjet\Models\EquipeProjet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\PkgRealisationTache\Services\SituationPedagogiqueService;

/**
 * Trait dédié aux requêtes de consultation (Getters), tris et filtres d'exécution de RealisationTache.
 */
trait RealisationTacheGetterTrait
{
    public const SITUATION_TRAVAIL       = SituationPedagogiqueService::SITUATION_TRAVAIL;
    public const SITUATION_BLOCAGE       = SituationPedagogiqueService::SITUATION_BLOCAGE;
    public const SITUATION_ATTENTE       = SituationPedagogiqueService::SITUATION_ATTENTE;
    public const SITUATION_TERMINEE      = SituationPedagogiqueService::SITUATION_TERMINEE;
    public const SITUATION_PAUSE_ABSENCE = SituationPedagogiqueService::SITUATION_PAUSE_ABSENCE;
    public const SITUATION_INACTIF_SEANCE = SituationPedagogiqueService::SITUATION_INACTIF_SEANCE;
    public const SITUATION_INACTIF_JOUR   = SituationPedagogiqueService::SITUATION_INACTIF_JOUR;
    public const SITUATION_CUSTOM        = SituationPedagogiqueService::SITUATION_CUSTOM;

    public const SITUATION_WORKFLOW_MAP = SituationPedagogiqueService::SITUATION_WORKFLOW_MAP;

    /**
     * Prépare les données additionnelles pour la vue index (TableUI).
     *
     * @param array $params
     * @return array
     */
    public function prepareDataForIndexView(array $params = []): array
    {
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

        $baseData['revisionsBeforePriorityGrouped'] = $revisionsGrouped;

        // Toutes les RT des mêmes projets (pour trouver la précédente par ordre-1)
        $previousTasksGrouped = RealisationTache::query()
            ->whereIn('realisation_projet_id', $realisationTaches->pluck('realisation_projet_id'))
            ->with(['tache', 'etatRealisationTache.workflowTache'])
            ->get()
            ->groupBy('realisation_projet_id');

        $baseData['previousTasksGrouped'] = $previousTasksGrouped;

        // WorkflowTaches disponibles pour la vue
        $workflowService = new WorkflowTacheService();
        $workflowTachesList = $workflowService->all();
        $baseData['workflowTachesList'] = $workflowTachesList;
        $baseData['situationWorkflowMap'] = self::SITUATION_WORKFLOW_MAP;

        // Données d'inactivité si situation d'inactivité active
        $situation = $this->viewState->get('filter.realisationTache.situation_pedagogique') 
            ?? request('filter.realisationTache.situation_pedagogique')
            ?? request('situation_pedagogique');

        if ($situation === self::SITUATION_INACTIF_SEANCE || $situation === self::SITUATION_INACTIF_JOUR) {
            $mode = ($situation === self::SITUATION_INACTIF_JOUR) ? 'jour' : 'seance';
            $filterVariables = $this->viewState->getFilterVariables('realisationTache');
            $inactiviteData = $this->getInactiviteData($mode, $filterVariables);
            $baseData['inactiviteMetadata'] = $inactiviteData['metadata'];
            $baseData['inactiviteResume']   = $inactiviteData['resume'];
            $baseData['inactiviteSeanceNom'] = $inactiviteData['seance_nom'];
        } else {
            $baseData['inactiviteMetadata'] = [];
            $baseData['inactiviteResume']   = null;
            $baseData['inactiviteSeanceNom'] = null;
        }

        // Dispo dans Blade via compact_value
        if (isset($baseData['realisationTache_compact_value']) && is_array($baseData['realisationTache_compact_value'])) {
            $baseData['realisationTache_compact_value']['revisionsBeforePriorityGrouped'] = $revisionsGrouped;
            $baseData['realisationTache_compact_value']['previousTasksGrouped'] = $previousTasksGrouped;
            $baseData['realisationTache_compact_value']['workflowTachesList'] = $workflowTachesList;
            $baseData['realisationTache_compact_value']['situationWorkflowMap'] = self::SITUATION_WORKFLOW_MAP;
            $baseData['realisationTache_compact_value']['inactiviteMetadata'] = $baseData['inactiviteMetadata'];
            $baseData['realisationTache_compact_value']['inactiviteResume']   = $baseData['inactiviteResume'];
            $baseData['realisationTache_compact_value']['inactiviteSeanceNom'] = $baseData['inactiviteSeanceNom'];
        }

        return $baseData;
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
        $priorityOrdre = $current->tache->priorite ?? 0;

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
     * Tri par défaut :
     * 1️⃣ Trier par date de fin de l'affectation
     * 2️⃣ Ensuite par ordre de tâche
     *
     * @param mixed $query
     * @return mixed
     */
    public function defaultSort($query)
    {
        return $query
            ->join('realisation_projets', 'realisation_taches.realisation_projet_id', '=', 'realisation_projets.id')
            ->join('affectation_projets', 'realisation_projets.affectation_projet_id', '=', 'affectation_projets.id')
            ->join('taches', 'realisation_taches.tache_id', '=', 'taches.id')
            ->orderBy('affectation_projets.date_fin', 'desc')
            ->orderBy('taches.ordre', 'asc')
            ->select('realisation_taches.*');
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

    /**
     * Applique les filtres sur la requête RealisationTache.
     *
     * @param Builder $builder
     * @param mixed $model
     * @param array $filterVariables
     * @return void
     */
    public function filter(Builder $builder, $model, array $filterVariables): void
    {
        $equipeProjetId = $filterVariables['tache.equipe_projet_id'] ?? $filterVariables['equipe_projet_id'] ?? null;
        if (!empty($equipeProjetId)) {
            unset($filterVariables['tache.equipe_projet_id'], $filterVariables['equipe_projet_id']);

            $equipe = EquipeProjet::with('apprenants')->find($equipeProjetId);
            $apprenantIds = $equipe ? $equipe->apprenants->pluck('id')->toArray() : [];

            if (!empty($apprenantIds)) {
                $builder->where(function (Builder $query) use ($equipeProjetId, $apprenantIds) {
                    $query->whereHas('realisationProjet', function ($rp) use ($apprenantIds) {
                        $rp->whereIn('apprenant_id', $apprenantIds);
                    })
                    ->whereHas('tache', function ($q) use ($equipeProjetId) {
                        $q->where(function ($sq) use ($equipeProjetId) {
                            $sq->where('equipe_projet_id', $equipeProjetId)
                               ->orWhereNull('equipe_projet_id');
                        });
                    });
                });
            } else {
                $builder->whereRaw('1 = 0');
            }
        }

        // --- FILTRE SITUATION PEDAGOGIQUE ET ETATS PERSONNALISES ---
        $situation = $filterVariables['situation_pedagogique'] 
            ?? $this->viewState->get('filter.realisationTache.situation_pedagogique')
            ?? request('filter.realisationTache.situation_pedagogique')
            ?? request('situation_pedagogique');

        $customEtats = $filterVariables['custom_etats'] 
            ?? $this->viewState->get('filter.realisationTache.custom_etats')
            ?? request('custom_etats');

        unset($filterVariables['situation_pedagogique'], $filterVariables['custom_etats']);

        if (!empty($situation)) {
            $customEtatsArray = is_array($customEtats) ? $customEtats : (empty($customEtats) ? [] : explode(',', (string) $customEtats));
            $customEtatsArray = array_values(array_filter(array_map('trim', $customEtatsArray)));

            app(SituationPedagogiqueService::class)->applySituationFilter($builder, $situation, $customEtatsArray, $filterVariables);

            unset($filterVariables['etat_realisation_tache_id']);
            unset($filterVariables['etatRealisationTache.WorkflowTache.Code']);
        }

        parent::filter($builder, $model, $filterVariables);
    }

    /**
     * Analyse et détecte les apprenants inactifs durant la séance en cours ou la journée.
     * Délégué à SituationPedagogiqueService.
     *
     * @param string $mode 'seance' ou 'jour'
     * @param array $activeFilters Filtres actifs transmis par le ViewState ou la requête
     * @return array{target_tache_ids: int[], metadata: array, resume: string, seance_nom: string, nb_inactifs: int}
     */
    public function getInactiviteData(string $mode = 'seance', array $activeFilters = []): array
    {
        return app(SituationPedagogiqueService::class)->getInactiviteData($mode, $activeFilters);
    }
}
