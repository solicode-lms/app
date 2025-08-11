<?php


namespace Modules\PkgApprentissage\Services;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaPrototypeService;

/**
 * Classe RealisationUaPrototypeService pour gérer la persistance de l'entité RealisationUaPrototype.
 */
class RealisationUaPrototypeService extends BaseRealisationUaPrototypeService
{

    /**
     * Job déclenché après mise à jour d'un RealisationUaPrototype.
     *
     * @param int    $id
     * @param string $token
     * @return void
     */
    public function updatedObserverJob(int $id, string $token): void
    {
        $jobManager = new JobManager($token);
        $changedFields = $jobManager->getChangedFields();

        /** @var RealisationUaPrototype|null $realisationUaPrototype */
        $realisationUaPrototype = RealisationUaPrototype::find($id);
        if (! $realisationUaPrototype) {
            return;
        }

        // 🔹 Si la note ou le barème a changé
        if (
            $jobManager->isDirty('note') ||
            $jobManager->isDirty('bareme')
        ) {
            if ($realisationUaPrototype->realisation_tache_id) {
                $realisationTache = $realisationUaPrototype->realisationTache;

                if ($realisationTache) {
                    // Récupérer tous les prototypes liés à cette tâche
                    $prototypes = RealisationUaPrototype::where('realisation_tache_id', $realisationTache->id)->get();

                    // Calcul de la note totale (max = barème)
                    $noteTotale = $prototypes->sum(function ($proto) {
                        return min($proto->note ?? 0, $proto->bareme ?? 0);
                    });

                    // Label du job
                    $jobManager->setLabel("Mise à jour de la note de la tâche #{$realisationTache->id}");

                    // ⚡ Mise à jour pour déclencher l’updatedObserverJob
                    $realisationTache->update([
                        'note' => round($noteTotale, 2)
                    ]);
                }
            }
        }
    }


}
