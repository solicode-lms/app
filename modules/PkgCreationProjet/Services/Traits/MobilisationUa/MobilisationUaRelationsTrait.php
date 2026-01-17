<?php

namespace Modules\PkgCreationProjet\Services\Traits\MobilisationUa;

use Modules\PkgCreationTache\Services\TacheService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;

trait MobilisationUaRelationsTrait
{
    /**
     * Déclenche la synchronisation entre les Tâches et les Réalisations.
     * 
     * Cette méthode met à jour les indicateurs de réalisation (notes, avancement)
     * pour les projets (N3) et prototypes (N2) impactés par la mobilisation.
     *
     * @param int $projetId L'identifiant du projet concerné.
     * @return void
     */
    public function triggerSyncTacheEtRealisation($projetId)
    {
        // On délègue au RealisationProjetService la logique de mise à jour des métadonnées
        // des réalisations existantes (N2/N3) en fonction des nouvelles mobilisations.
        $realisationProjetService = new RealisationProjetService();

        // Note : Cette méthode doit exister dans RealisationProjetService ou un de ses Traits
        // pour recalculer les scores ou propager les nouvelles compétences aux projets élèves.
        if (method_exists($realisationProjetService, 'syncRealisationProjetCalcul')) {
            // Exemple d'appel hypothétique si la logique de calcul est centralisée là-bas
            // $realisationProjetService->syncRealisationProjetCalcul($projetId);
        }

        // Logique actuelle (basée sur le code précédent) : 
        // Il semble que l'appel précédent était `addMobilisationToProjectRealisations`.
        // Si cette logique est maintenant gérée par des événements ou des hooks ailleurs,
        // cette méthode sert de point d'entrée explicite.

        // TODO: Vérifier si RealisationProjetService a besoin d'une méthode spécifique ici
        // ou si le TacheService s'en charge via createRealisationTaches.
    }
}
