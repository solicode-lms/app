<?php


namespace Modules\PkgApprentissage\Services;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationUaProjet;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaProjetService;

/**
 * Classe RealisationUaProjetService pour gérer la persistance de l'entité RealisationUaProjet.
 */
class RealisationUaProjetService extends BaseRealisationUaProjetService
{
    
    public function updatedObserverJob(int $id, string $token): void
    {
        $jobManager = new JobManager($token);
        $changedFields = $jobManager->getChangedFields();

        $realisationUaProjet = $this->find($id);
        if (! $realisationUaProjet) {
            return;
        }

        // 2️⃣ Recalculer la note de la tâche à partir des RealisationUaProjets
        if ($realisationUaProjet->realisation_tache_id) {
            $tache = $realisationUaProjet->realisationTache;

            if ($tache) {
                $realisationUaProjets = RealisationUaProjet::where('realisation_tache_id', $tache->id)->get();

                $noteTotale = $realisationUaProjets->sum(function ($projet) {
                    return min($projet->note ?? 0, $projet->bareme ?? 0);
                });

                $jobManager->setLabel("Mise à jour de la note de la tâche #{$tache->id}");
                $tache->update([
                    'note' => round($noteTotale, 2)
                ]);
                $jobManager->tick();
            }
        }

        $jobManager->finish();
    }

 
}
