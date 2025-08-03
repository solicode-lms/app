<?php


namespace Modules\PkgApprentissage\Services;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaPrototypeService;

/**
 * Classe RealisationUaPrototypeService pour gérer la persistance de l'entité RealisationUaPrototype.
 */
class RealisationUaPrototypeService extends BaseRealisationUaPrototypeService
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
