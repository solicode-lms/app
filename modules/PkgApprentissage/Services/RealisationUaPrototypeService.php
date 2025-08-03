<?php


namespace Modules\PkgApprentissage\Services;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaPrototypeService;

/**
 * Classe RealisationUaPrototypeService pour gérer la persistance de l'entité RealisationUaPrototype.
 */
class RealisationUaPrototypeService extends BaseRealisationUaPrototypeService
{
    public function afterUpdateRules($realisationUaPrototype): void
    {
        // Détection du changement de note ou de barème
        if ($realisationUaPrototype->wasChanged(['note', 'bareme'])) {
            if ($realisationUaPrototype->realisationUa) {
                (new RealisationUaService())->calculerProgressionEtNote($realisationUaPrototype->realisationUa);
            }
        }
    }

}
