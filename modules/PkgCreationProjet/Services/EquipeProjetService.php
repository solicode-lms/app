<?php
namespace Modules\PkgCreationProjet\Services;

use Modules\PkgCreationProjet\Models\EquipeProjet;
use Modules\PkgCreationProjet\Services\Base\BaseEquipeProjetService;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgNotification\Services\NotificationService;
use Modules\PkgNotification\Enums\NotificationType;
use Modules\Core\App\Exceptions\BlException;

/**
 * Classe EquipeProjetService pour gérer la persistance de l'entité EquipeProjet.
 */
class EquipeProjetService extends BaseEquipeProjetService
{
    /**
     * Réalisations de tâches en état TODO à supprimer après mise à jour, indexées par ID d'équipe.
     *
     * @var array<int|string, array<\Modules\PkgRealisationTache\Models\RealisationTache>>
     */
    protected array $realisationsASupprimerParEquipe = [];

    /**
     * Règle exécutée avant la mise à jour d'une EquipeProjet.
     * Vérifie que le retrait éventuel d'apprenants est autorisé :
     * seules les réalisations en état 'TODO' peuvent être supprimées.
     * Si une réalisation a progressé au-delà de 'TODO', une BlException est levée pour bloquer l'enregistrement.
     *
     * @param array $data
     * @param int|string $id
     * @return void
     * @throws BlException
     */
    public function beforeUpdateRules(array &$data, $id): void
    {
        if (array_key_exists('apprenants', $data)) {
            $equipe = $this->find($id);
            if ($equipe) {
                $anciensIds = $equipe->apprenants()->pluck('apprenants.id')->map(fn($v) => (int)$v)->toArray();
                $nouveauxIds = array_map('intval', (array) ($data['apprenants'] ?? []));
                $apprenantsRetiresIds = array_values(array_diff($anciensIds, $nouveauxIds));

                if (!empty($apprenantsRetiresIds)) {
                    $tacheIds = $equipe->taches()->pluck('id')->toArray();

                    if (!empty($tacheIds)) {
                        $realisationsASupprimer = [];
                        $erreursBloquantes = [];

                        foreach ($apprenantsRetiresIds as $apprenantId) {
                            $realisations = RealisationTache::whereIn('tache_id', $tacheIds)
                                ->whereHas('realisationProjet', function ($q) use ($apprenantId, $equipe) {
                                    $q->where('apprenant_id', $apprenantId)
                                      ->whereHas('affectationProjet', function ($ap) use ($equipe) {
                                          $ap->where('projet_id', $equipe->projet_id);
                                      });
                                })
                                ->with(['etatRealisationTache.workflowTache', 'tache', 'realisationProjet.apprenant'])
                                ->get();

                            if ($realisations->isEmpty()) {
                                continue;
                            }

                            // Vérifier si des réalisations ne sont pas à l'état TODO
                            $nonTodoRealisations = $realisations->filter(function ($rt) {
                                return $rt->etatRealisationTache?->workflowTache?->code !== 'TODO';
                            });

                            if ($nonTodoRealisations->isNotEmpty()) {
                                $apprenant = $realisations->first()->realisationProjet?->apprenant;
                                $nomApprenant = $apprenant ? "{$apprenant->nom} {$apprenant->prenom}" : "l'apprenant #{$apprenantId}";
                                $detailsTaches = $nonTodoRealisations->map(function ($rt) {
                                    $etatNom = $rt->etatRealisationTache?->nom ?? 'Non défini';
                                    return "« {$rt->tache->titre} » (état : {$etatNom})";
                                })->implode(', ');

                                $erreursBloquantes[] = "{$nomApprenant} : {$detailsTaches}";
                            }

                            // Si toutes les réalisations sont à l'état TODO, on les mémorise pour suppression dans afterUpdateRules
                            foreach ($realisations as $rt) {
                                $realisationsASupprimer[] = $rt;
                            }
                        }

                        if (!empty($erreursBloquantes)) {
                            $detailsMsg = implode(' ; ', $erreursBloquantes);
                            throw new BlException(
                                "Impossible d'enregistrer : vous ne pouvez pas retirer d'apprenant ayant des réalisations de tâches qui ne sont pas à l'état « À faire » (TODO).</br>" .
                                "Tâches concernées : {$detailsMsg}.</br>" .
                                "Veuillez d'abord remettre l'état de ces réalisations de tâches à « À faire » (TODO) avant de pouvoir retirer l'apprenant de l'équipe."
                            );
                        }

                        if (!empty($realisationsASupprimer)) {
                            $this->realisationsASupprimerParEquipe[$id] = $realisationsASupprimer;
                        }
                    }
                }
            }
        }
    }

    /**
     * Hook exécuté après la création d'une EquipeProjet.
     *
     * @param EquipeProjet $item
     * @return void
     */
    public function afterCreateRules($item)
    {
        $this->affecterTachesEquipeAuxApprenants($item);
    }

    /**
     * Hook exécuté après la mise à jour d'une EquipeProjet.
     *
     * @param EquipeProjet $item
     * @param int|null $id
     * @return void
     */
    public function afterUpdateRules($item, $id = null)
    {
        $equipeId = $id ?? $item->id;

        // 1. Suppression des réalisations en état TODO pour les apprenants retirés
        if (!empty($this->realisationsASupprimerParEquipe[$equipeId])) {
            $realisationTacheService = app(RealisationTacheService::class);
            foreach ($this->realisationsASupprimerParEquipe[$equipeId] as $rt) {
                $realisationTacheService->destroy($rt->id);
            }
            unset($this->realisationsASupprimerParEquipe[$equipeId]);
        }

        // 2. Affectation automatique aux apprenants membres actuels / nouveaux
        $this->affecterTachesEquipeAuxApprenants($item);
    }

    /**
     * Affecte automatiquement les tâches spécifiques de l'équipe aux apprenants membres.
     *
     * @param EquipeProjet $equipeProjet
     * @return void
     */
    public function affecterTachesEquipeAuxApprenants(EquipeProjet $equipeProjet): void
    {
        $equipeProjet->loadMissing(['taches', 'apprenants.user', 'projet']);

        $taches = $equipeProjet->taches;
        if ($taches->isEmpty()) {
            return;
        }

        $apprenants = $equipeProjet->apprenants;
        if ($apprenants->isEmpty()) {
            return;
        }

        $projetId = $equipeProjet->projet_id;
        $formateurId = $equipeProjet->projet?->formateur_id;

        $realisationTacheService = app(RealisationTacheService::class);
        $etatService = app(EtatRealisationTacheService::class);
        $notificationService = app(NotificationService::class);

        $etatInitial = $formateurId
            ? $etatService->getDefaultEtatByFormateurId($formateurId)
            : null;

        foreach ($apprenants as $apprenant) {
            // Retrouver la réalisation de projet de l'apprenant pour ce projet
            $realisationProjet = RealisationProjet::where('apprenant_id', $apprenant->id)
                ->whereHas('affectationProjet', function ($q) use ($projetId) {
                    $q->where('projet_id', $projetId);
                })
                ->first();

            if (!$realisationProjet) {
                continue;
            }

            foreach ($taches as $tache) {
                $realisationTache = $realisationTacheService->initialiserRealisationTache($tache, $realisationProjet, $etatInitial);

                if ($realisationTache) {
                    $userApprenantId = $apprenant->user_id;
                    if ($userApprenantId) {
                        $notificationService->sendNotificationToReadData(
                            'realisationTache',
                            $realisationTache->id,
                            $userApprenantId,
                            "Nouvelle tâche d'équipe attribuée : {$tache->titre}",
                            "Vous avez une nouvelle tâche à réaliser pour l'équipe {$equipeProjet->nom} : {$tache->titre}",
                            NotificationType::NOUVELLE_TACHE->value
                        );
                    }
                }
            }
        }
    }
}
