<?php
namespace Modules\PkgCreationProjet\Services;

use Modules\PkgCreationProjet\Models\EquipeProjet;
use Modules\PkgCreationProjet\Services\Base\BaseEquipeProjetService;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgNotification\Services\NotificationService;
use Modules\PkgNotification\Enums\NotificationType;

/**
 * Classe EquipeProjetService pour gérer la persistance de l'entité EquipeProjet.
 */
class EquipeProjetService extends BaseEquipeProjetService
{
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
