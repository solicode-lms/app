<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Services;
use Modules\PkgCreationProjet\Services\Base\BaseMobilisationUaService;

/**
 * Classe MobilisationUaService pour gérer la persistance de l'entité MobilisationUa.
 */
class MobilisationUaService extends BaseMobilisationUaService
{
    public function afterCreateRules($item): void
    {
        if ($item instanceof \Modules\PkgCreationProjet\Models\MobilisationUa) {
            $realisationProjetService = new \Modules\PkgRealisationProjets\Services\RealisationProjetService();
            $realisationProjetService->addMobilisationToProjectRealisations($item->projet_id, $item);
        }
    }

    public function destroy($id)
    {
        $mobilisation = $this->find($id);

        $result = parent::destroy($id);

        if ($mobilisation) {
            $realisationProjetService = new \Modules\PkgRealisationProjets\Services\RealisationProjetService();
            $realisationProjetService->removeMobilisationFromProjectRealisations(
                $mobilisation->projet_id,
                $mobilisation->unite_apprentissage_id
            );
        }

        return $result;
    }
}
