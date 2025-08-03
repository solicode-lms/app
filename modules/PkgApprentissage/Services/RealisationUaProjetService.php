<?php


namespace Modules\PkgApprentissage\Services;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaProjetService;

/**
 * Classe RealisationUaProjetService pour gérer la persistance de l'entité RealisationUaProjet.
 */
class RealisationUaProjetService extends BaseRealisationUaProjetService
{
    
  public function afterUpdateRules($realisationUaProjet): void
    {
        // Détection du changement de note ou de barème
        if ($realisationUaProjet->wasChanged(['note', 'bareme'])) {
            if ($realisationUaProjet->realisationUa) {
                (new RealisationUaService())->calculerProgressionEtNote($realisationUaProjet->realisationUa);
            }
        }
    }
    
 
}
