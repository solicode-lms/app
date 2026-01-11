<?php

namespace Modules\PkgRealisationProjets\Services\Traits\RealisationProjet;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgApprentissage\Models\RealisationUa;

trait RealisationProjetJobTrait
{
    public function deletedObserverJob(int $id, string $token): void
    {
        $jobManager = new JobManager($token);
        $payload = $jobManager->getPayload();

        $uaIds = collect($payload['ua_ids'] ?? []);
        $realisation_chapitres_ids = collect($payload['realisation_chapitres_ids'] ?? []);

        $total = 0;

        // 1️⃣ Chapitres (N1)
        if ($realisation_chapitres_ids->isNotEmpty()) {
            $total++;
        }

        // 2️⃣ UA (N2 / N3)
        $total += $uaIds->count();

        $jobManager->initProgress($total);

        // 1️⃣ Chapitre (N1)
        if ($realisation_chapitres_ids->isNotEmpty()) {
            $jobManager->setLabel("Mise à jour des chapitres");
            $realisationChapitreService = new RealisationChapitreService();
            $realisationChapitreService->calculerProgressionDepuisRealisationChapitresIds($realisation_chapitres_ids);
            $jobManager->tick();
        }

        // 2️⃣ Unités d'apprentissage (UA)
        if ($uaIds->isNotEmpty()) {
            $realisationUaService = new RealisationUaService();
            $uas = RealisationUa::whereIn('id', $uaIds)->get();
            foreach ($uas as $ua) {
                $jobManager->setLabel("Calcul progression pour UA #{$ua}");
                $realisationUaService->calculerProgression($ua);
                $jobManager->tick();
            }
        }

        $jobManager->finish();
    }
}
