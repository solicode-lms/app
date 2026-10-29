<?php

namespace Modules\PkgRealisationTache\Services;

use Illuminate\Database\Eloquent\Builder;
use Modules\PkgRealisationTache\Models\RealisationTache;

/**
 * Service métier dédié à la modélisation et au pilotage des Situations Pédagogiques.
 * 
 * Ce service encapsule les macro-situations d'apprentissage (Blocage, Attente, En pause/Absent,
 * Terminé, Inactivité de séance/journée), le mapping avec les états WorkflowTache,
 * et orchestre l'application des filtres pour TableUI.
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
     * Analyse et détecte les apprenants inactifs durant la séance en cours ou la journée.
     * Délégué au service InactivitePedagogiqueService.
     *
     * @param string $mode 'seance' ou 'jour'
     * @param array $activeFilters Filtres contextuels actifs
     * @return array{target_tache_ids: int[], metadata: array, resume: string, seance_nom: string, nb_inactifs: int}
     */
    public function getInactiviteData(string $mode = 'seance', array $activeFilters = []): array
    {
        return app(InactivitePedagogiqueService::class)->getInactiviteData($mode, $activeFilters);
    }

    /**
     * Identifie la tâche prioritaire d'un apprenant inactif selon les règles métier Solicode.
     * Délégué au service InactivitePedagogiqueService.
     *
     * @param int $apprenantId
     * @param int|null $affectationProjetId
     * @param int|null $equipeProjetId
     * @return RealisationTache|null
     */
    public function findPrioritaryTaskForInactiveApprenant(int $apprenantId, ?int $affectationProjetId = null, ?int $equipeProjetId = null): ?RealisationTache
    {
        return app(InactivitePedagogiqueService::class)->findPrioritaryTaskForInactiveApprenant($apprenantId, $affectationProjetId, $equipeProjetId);
    }
}
