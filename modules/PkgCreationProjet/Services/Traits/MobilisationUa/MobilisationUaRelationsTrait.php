<?php

namespace Modules\PkgCreationProjet\Services\Traits\MobilisationUa;

use Modules\PkgCreationTache\Services\TacheService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;

trait MobilisationUaRelationsTrait
{
    /**
     * Synchronise les tâches N2 (Prototype) et N3 (Projet) avec les UA mobilisées.
     * 
     * Cette méthode est appelée après la création, modification ou suppression d'une mobilisation UA.
     * Elle met à jour les réalisations de compétences (RealisationUaPrototype/Projet) pour toutes
     * les tâches N2/N3 existantes du projet en :
     * 1. Supprimant les liens vers des UA qui ne sont plus mobilisées.
     * 2. Ajoutant les nouveaux liens vers les UA nouvellement mobilisées.
     * 
     * @param int $projetId L'identifiant du projet concerné.
     * @return void
     */
    public function syncN2N3TasksWithMobilisedUa($projetId)
    {
        $tacheService = new TacheService();

        // Récupérer toutes les tâches N2 et N3 du projet
        $taches = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
            ->whereHas('phaseEvaluation', function ($q) {
                $q->whereIn('code', ['N2', 'N3']);
            })
            ->get();

        // Pour chaque tâche N2/N3, déclencher un update qui activera les hooks
        // Le hook afterUpdateRules appellera automatiquement syncRealisationPrototypeOrProjet()
        foreach ($taches as $tache) {
            $tacheService->update($tache->id, [
                'updated_at' => now() // Forcer la mise à jour pour déclencher les hooks
            ]);
        }
    }
}
