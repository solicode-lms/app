<?php

namespace Modules\PkgRealisationTache\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationProjets\Models\EquipeProjet;
use Modules\PkgRealisationProjets\Models\AffectationProjet;

/**
 * Service métier dédié à la modélisation et au pilotage des Situations Pédagogiques.
 * 
 * Ce service encapsule les macro-situations d'apprentissage (Blocage, Attente, En pause/Absent,
 * Terminé, Inactivité de séance/journée), le mapping avec les états WorkflowTache,
 * ainsi que le moteur analytique de détection des apprenants inactifs.
 */
class SituationPedagogiqueService
{
    public const SITUATION_TRAVAIL       = 'SITUATION_TRAVAIL';
    public const SITUATION_BLOCAGE       = 'SITUATION_BLOCAGE';
    public const SITUATION_ATTENTE       = 'SITUATION_ATTENTE';
    public const SITUATION_TERMINEE      = 'SITUATION_TERMINEE';
    public const SITUATION_PAUSE_ABSENCE = 'SITUATION_PAUSE_ABSENCE';
    public const SITUATION_INACTIF_SEANCE = 'SITUATION_INACTIF_SEANCE';
    public const SITUATION_INACTIF_JOUR   = 'SITUATION_INACTIF_JOUR';
    public const SITUATION_CUSTOM        = 'SITUATION_CUSTOM';

    /**
     * Mapping exhaustif des situations pédagogiques vers les codes du WorkflowTache.
     */
    public const SITUATION_WORKFLOW_MAP = [
        self::SITUATION_TRAVAIL => [
            'IN_PROGRESS', 
            'REVISION_NECESSAIRE', 
            'EN_REMEDIATION', 
            'IN_LIVE_CODING', 
        ],
        self::SITUATION_BLOCAGE => [
            'EN_REMEDIATION', 
            'REVISION_NECESSAIRE', 
            'LATE', 
        ],
        self::SITUATION_ATTENTE => [
            'TO_APPROVE', 
            'READY_FOR_LIVE_CODING', 
            'A_VALIDER_LIVE_CODING'
        ],
        self::SITUATION_TERMINEE => [
            'APPROVED',
            'NOT_VALIDATED', 
            'VALIDE_INTERMEDIAIRE',
        ],
        self::SITUATION_PAUSE_ABSENCE => [
            'PAUSED',
            'ABSENT',
        ],
    ];

    /**
     * Cache mémoire des calculs d'inactivité par requête.
     */
    protected array $cachedInactiviteData = [];

    /**
     * Retourne le mapping situations pédagogiques <-> codes WorkflowTache.
     *
     * @return array<string, string[]>
     */
    public function getSituationWorkflowMap(): array
    {
        return self::SITUATION_WORKFLOW_MAP;
    }

    /**
     * Retourne la liste des options pour le filtre des situations pédagogiques dans TableUI.
     *
     * @return array<int, array{id: string, label: string}>
     */
    public function getSituationFilterOptions(): array
    {
        return [
            ['id' => self::SITUATION_TRAVAIL, 'label' => '🔨 ' . __('PkgRealisationTache::realisationTache.situation_travail')],
            ['id' => self::SITUATION_BLOCAGE, 'label' => '⚠️ ' . __('PkgRealisationTache::realisationTache.situation_blocage')],
            ['id' => self::SITUATION_ATTENTE, 'label' => '⏳ ' . __('PkgRealisationTache::realisationTache.situation_attente')],
            ['id' => self::SITUATION_TERMINEE, 'label' => '✅ ' . __('PkgRealisationTache::realisationTache.situation_terminee')],
            ['id' => self::SITUATION_PAUSE_ABSENCE, 'label' => '⏸️ ' . __('PkgRealisationTache::realisationTache.situation_pause_absence')],
            ['id' => self::SITUATION_INACTIF_SEANCE, 'label' => '🚨 ' . __('PkgRealisationTache::realisationTache.situation_inactif_seance')],
            ['id' => self::SITUATION_INACTIF_JOUR, 'label' => '📅 ' . __('PkgRealisationTache::realisationTache.situation_inactif_jour')],
            ['id' => self::SITUATION_CUSTOM, 'label' => '🎯 ' . __('PkgRealisationTache::realisationTache.situation_custom')],
        ];
    }

    /**
     * Applique les contraintes de filtrage Eloquent selon la situation pédagogique sélectionnée.
     *
     * @param Builder $builder
     * @param string $situation
     * @param array $customEtatsArray Codes d'états personnalisés pour SITUATION_CUSTOM
     * @param array $activeFilters Filtres contextuels actifs
     * @return void
     */
    public function applySituationFilter(Builder $builder, string $situation, array $customEtatsArray = [], array $activeFilters = []): void
    {
        if (isset(self::SITUATION_WORKFLOW_MAP[$situation])) {
            $codes = self::SITUATION_WORKFLOW_MAP[$situation];
            $builder->whereHas('etatRealisationTache.workflowTache', function (Builder $q) use ($codes) {
                $q->whereIn('code', $codes);
            });
        } elseif ($situation === self::SITUATION_INACTIF_SEANCE || $situation === self::SITUATION_INACTIF_JOUR) {
            $mode = ($situation === self::SITUATION_INACTIF_JOUR) ? 'jour' : 'seance';
            $inactiviteData = $this->getInactiviteData($mode, $activeFilters);
            $targetIds = $inactiviteData['target_tache_ids'] ?? [];

            if (!empty($targetIds)) {
                $builder->whereIn('realisation_taches.id', $targetIds);
            } else {
                // Aucun inactif : restreindre la requête pour afficher 0 élément
                $builder->whereRaw('1 = 0');
            }
        } elseif ($situation === self::SITUATION_CUSTOM && !empty($customEtatsArray)) {
            $builder->whereHas('etatRealisationTache.workflowTache', function (Builder $q) use ($customEtatsArray) {
                $q->whereIn('code', $customEtatsArray);
            });
        }
    }

    /**
     * Analyse et détecte les apprenants inactifs durant la séance en cours ou la journée,
     * et identifie pour chacun leur tâche de travail prioritaire avec métadonnées d'inactivité
     * en respectant rigoureusement le périmètre des filtres actifs.
     *
     * @param string $mode 'seance' ou 'jour'
     * @param array $activeFilters Filtres contextuels actifs
     * @return array{target_tache_ids: int[], metadata: array, resume: string, seance_nom: string, nb_inactifs: int}
     */
    public function getInactiviteData(string $mode = 'seance', array $activeFilters = []): array
    {
        $seanceService = new SeanceService();

        if ($mode === 'jour') {
            [$start, $end] = $seanceService->getTodayTimeRange();
            $seanceNom = __('Journée en cours') . ' (' . __('depuis') . ' ' . $start->format('H:i') . ')';
        } else {
            $seance = $seanceService->getLastOrCurrentSeance();
            [$start, $end] = $seanceService->getSeanceTimeRange($seance);
            $seanceNom = $seance['nom'];
        }

        // Récupération des filtres contextuels
        $affectationProjetId = $activeFilters['RealisationProjet.Affectation_projet_id']
            ?? $activeFilters['RealisationProjet_Affectation_projet_id']
            ?? $activeFilters['affectation_projet_id']
            ?? request('filter.realisationTache.RealisationProjet.Affectation_projet_id')
            ?? request('RealisationProjet.Affectation_projet_id')
            ?? request('RealisationProjet_Affectation_projet_id');

        $groupeId = $activeFilters['RealisationProjet.AffectationProjet.Groupe_id']
            ?? $activeFilters['RealisationProjet_AffectationProjet_Groupe_id']
            ?? $activeFilters['groupe_id']
            ?? request('filter.realisationTache.RealisationProjet.AffectationProjet.Groupe_id')
            ?? request('RealisationProjet.AffectationProjet.Groupe_id');

        $filiereId = $activeFilters['RealisationProjet.AffectationProjet.Groupe.Filiere_id']
            ?? $activeFilters['RealisationProjet_AffectationProjet_Groupe_Filiere_id']
            ?? $activeFilters['filiere_id']
            ?? request('filter.realisationTache.RealisationProjet.AffectationProjet.Groupe.Filiere_id')
            ?? request('RealisationProjet.AffectationProjet.Groupe.Filiere_id');

        $equipeProjetId = $activeFilters['tache.equipe_projet_id']
            ?? $activeFilters['equipe_projet_id']
            ?? request('filter.realisationTache.tache.equipe_projet_id')
            ?? request('filter.realisationTache.equipe_projet_id')
            ?? request('tache.equipe_projet_id');

        $targetApprenantId = $activeFilters['RealisationProjet.Apprenant_id']
            ?? $activeFilters['RealisationProjet_Apprenant_id']
            ?? $activeFilters['apprenant_id']
            ?? request('filter.realisationTache.RealisationProjet.Apprenant_id')
            ?? request('RealisationProjet.Apprenant_id');

        // Clé de cache pour éviter de recalculer inutilement durant la même requête
        $cacheKey = $mode . '_' . md5(json_encode([
            $affectationProjetId, $groupeId, $filiereId, $equipeProjetId, $targetApprenantId
        ]));

        if (isset($this->cachedInactiviteData[$cacheKey])) {
            return $this->cachedInactiviteData[$cacheKey];
        }

        // 1. Apprenants ayant enregistré au moins un changement d'état sur la période
        // Seules les actions menées par l'apprenant lui-même (user_id) comptabilisent de l'activité
        $activeApprenantIds = HistoriqueRealisationTache::query()
            ->whereBetween('historique_realisation_taches.dateModification', [$start, $end])
            ->join('realisation_taches', 'historique_realisation_taches.realisation_tache_id', '=', 'realisation_taches.id')
            ->join('realisation_projets', 'realisation_taches.realisation_projet_id', '=', 'realisation_projets.id')
            ->join('apprenants', 'realisation_projets.apprenant_id', '=', 'apprenants.id')
            ->whereColumn('historique_realisation_taches.user_id', '=', 'apprenants.user_id')
            ->pluck('realisation_projets.apprenant_id')
            ->unique()
            ->toArray();

        // 2. Périmètre des apprenants supervisés selon le rôle
        $user = Auth::user();
        $apprenants = match (true) {
            $user && $user->hasRole(Role::FORMATEUR_ROLE) => (new FormateurService())->getApprenants(session('formateur_id')),
            $user && $user->hasRole(Role::APPRENANT_ROLE) => (new ApprenantService())->getApprenantsDeGroupe(session('apprenant_id')),
            default => Apprenant::all(),
        };

        $supervisedApprenantIds = $apprenants->pluck('id')->toArray();
        if (empty($supervisedApprenantIds)) {
            $supervisedApprenantIds = RealisationProjet::query()
                ->pluck('apprenant_id')
                ->unique()
                ->toArray();
        }

        // 3. Application des filtres contextuels sur le périmètre des apprenants
        if (!empty($targetApprenantId)) {
            $supervisedApprenantIds = array_values(array_intersect($supervisedApprenantIds, [(int) $targetApprenantId]));
        }

        if (!empty($affectationProjetId)) {
            $affectationApprenants = RealisationProjet::query()
                ->where('affectation_projet_id', $affectationProjetId)
                ->pluck('apprenant_id')
                ->unique()
                ->toArray();

            if (empty($affectationApprenants)) {
                $affectation = AffectationProjet::find($affectationProjetId);
                if ($affectation?->sousGroupe) {
                    $affectationApprenants = $affectation->sousGroupe->apprenants->pluck('id')->toArray();
                } elseif ($affectation?->groupe) {
                    $affectationApprenants = $affectation->groupe->apprenants->pluck('id')->toArray();
                }
            }

            $supervisedApprenantIds = array_values(array_intersect($supervisedApprenantIds, $affectationApprenants));
        }

        if (!empty($groupeId) && empty($affectationProjetId)) {
            $groupeApprenants = Apprenant::where('groupe_id', $groupeId)->pluck('id')->toArray();
            $supervisedApprenantIds = array_values(array_intersect($supervisedApprenantIds, $groupeApprenants));
        }

        if (!empty($filiereId) && empty($groupeId) && empty($affectationProjetId)) {
            $filiereApprenants = Apprenant::whereHas('groupe', fn($q) => $q->where('filiere_id', $filiereId))->pluck('id')->toArray();
            $supervisedApprenantIds = array_values(array_intersect($supervisedApprenantIds, $filiereApprenants));
        }

        if (!empty($equipeProjetId)) {
            $equipe = EquipeProjet::with('apprenants')->find($equipeProjetId);
            $equipeApprenants = $equipe ? $equipe->apprenants->pluck('id')->toArray() : [];
            $supervisedApprenantIds = array_values(array_intersect($supervisedApprenantIds, $equipeApprenants));
        }

        // 4. Déduction des apprenants inactifs dans le périmètre filtré
        $inactiveApprenantIds = array_values(array_diff($supervisedApprenantIds, $activeApprenantIds));

        $targetTaches = [];
        $metadataByTacheId = [];
        $now = Carbon::now();

        foreach ($inactiveApprenantIds as $apprenantId) {
            $tache = $this->findPrioritaryTaskForInactiveApprenant($apprenantId, $affectationProjetId, $equipeProjetId);

            if ($tache) {
                // Recherche de la toute dernière entrée d'historique enregistrée par l'apprenant lui-même
                $apprenantUser = Apprenant::find($apprenantId);
                $apprenantUserId = $apprenantUser?->user_id;

                $dernierHistoriqueQuery = HistoriqueRealisationTache::query()
                    ->join('realisation_taches', 'historique_realisation_taches.realisation_tache_id', '=', 'realisation_taches.id')
                    ->join('realisation_projets', 'realisation_taches.realisation_projet_id', '=', 'realisation_projets.id')
                    ->where('realisation_projets.apprenant_id', $apprenantId);

                if ($apprenantUserId) {
                    $dernierHistoriqueQuery->where('historique_realisation_taches.user_id', $apprenantUserId);
                }

                $dernierHistorique = $dernierHistoriqueQuery
                    ->latest('historique_realisation_taches.dateModification')
                    ->select('historique_realisation_taches.*')
                    ->first();

                if ($dernierHistorique && $dernierHistorique->dateModification) {
                    $derniereDate = Carbon::parse($dernierHistorique->dateModification);
                    $dureeMinutes = max(0, (int) $derniereDate->diffInMinutes($now));
                    $dernierChangementStr = $derniereDate->isToday() 
                        ? $derniereDate->format('H:i') 
                        : $derniereDate->format('d/m/Y H:i');
                    $dateExacteStr = $derniereDate->format('d/m/Y H:i:s');
                } else {
                    $dureeMinutes = max(0, (int) $start->diffInMinutes($now));
                    $dernierChangementStr = __('Aucun événement');
                    $dateExacteStr = __('Aucun événement enregistré par l\'apprenant');
                }

                $gravite = ($dureeMinutes >= 75) ? 'danger' : (($dureeMinutes >= 30) ? 'warning' : 'info');

                $targetTaches[$tache->id] = $dureeMinutes;
                $metadataByTacheId[$tache->id] = [
                    'duree_minutes' => $dureeMinutes,
                    'duree_formatee' => $seanceService->formatDuree($dureeMinutes),
                    'dernier_changement' => $dernierChangementStr,
                    'date_exacte' => $dateExacteStr,
                    'seance_nom' => $seanceNom,
                    'gravite' => $gravite,
                    'apprenant_id' => $apprenantId,
                ];
            }
        }

        // Tri décroissant par durée d'inactivité
        arsort($targetTaches);
        $sortedTacheIds = array_keys($targetTaches);
        $nbInactifs = count($sortedTacheIds);

        $resume = trans_choice(
            '{0} Aucun apprenant inactif|{1} :count apprenant inactif détecté (:seance)|[2,*] :count apprenants inactifs détectés (:seance)',
            $nbInactifs,
            ['count' => $nbInactifs, 'seance' => $seanceNom]
        );

        $result = [
            'target_tache_ids' => $sortedTacheIds,
            'metadata' => $metadataByTacheId,
            'resume' => $resume,
            'seance_nom' => $seanceNom,
            'nb_inactifs' => $nbInactifs,
        ];

        $this->cachedInactiviteData[$cacheKey] = $result;
        return $result;
    }

    /**
     * Identifie la tâche prioritaire d'un apprenant inactif selon les règles métier Solicode :
     * 1. Tâche en cours de travail (IN_PROGRESS, REVISION_NECESSAIRE, EN_REMEDIATION, PAUSED, ABSENT)
     * 2. Première tâche TODO ordonnée
     * 3. Dernière tâche mise à jour
     *
     * @param int $apprenantId
     * @param int|null $affectationProjetId
     * @param int|null $equipeProjetId
     * @return RealisationTache|null
     */
    public function findPrioritaryTaskForInactiveApprenant(int $apprenantId, ?int $affectationProjetId = null, ?int $equipeProjetId = null): ?RealisationTache
    {
        $baseTaskQuery = function() use ($apprenantId, $affectationProjetId, $equipeProjetId) {
            $q = RealisationTache::query()
                ->whereHas('realisationProjet', function ($rq) use ($apprenantId, $affectationProjetId) {
                    $rq->where('apprenant_id', $apprenantId);
                    if (!empty($affectationProjetId)) {
                        $rq->where('affectation_projet_id', $affectationProjetId);
                    }
                });

            if (!empty($equipeProjetId)) {
                $q->whereHas('tache', function ($tq) use ($equipeProjetId) {
                    $tq->where('equipe_projet_id', $equipeProjetId)
                       ->orWhereNull('equipe_projet_id');
                });
            }

            return $q;
        };

        // Priorité 1 : Tâche en cours de travail ou absence
        $tache = $baseTaskQuery()
            ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                $q->whereIn('code', ['IN_PROGRESS', 'REVISION_NECESSAIRE', 'EN_REMEDIATION', 'PAUSED', 'ABSENT']);
            })
            ->first();

        // Priorité 2 : Première tâche TODO à faire
        if (!$tache) {
            $tache = $baseTaskQuery()
                ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                    $q->where('code', 'TODO');
                })
                ->join('taches', 'realisation_taches.tache_id', '=', 'taches.id')
                ->orderBy('taches.ordre', 'asc')
                ->select('realisation_taches.*')
                ->first();
        }

        // Priorité 3 : Dernière tâche modifiée
        if (!$tache) {
            $tache = $baseTaskQuery()
                ->latest('realisation_taches.updated_at')
                ->first();
        }

        return $tache;
    }
}
