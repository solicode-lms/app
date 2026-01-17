<?php

namespace Modules\PkgCreationProjet\Services\Traits\MobilisationUa;

use Modules\PkgCompetences\Models\UniteApprentissage;

trait MobilisationUaDataCalculTrait
{
    /**
     * Enrichit les données avant traitement.
     */
    public function dataCalcul($data)
    {
        // Appel de la méthode parent si elle existe (via __call ou héritage direct dans le Service)
        // Note: Dans un trait, parent:: fait référence à la classe parente de la classe qui utilise le trait.
        // BaseService a une méthode dataCalcul.
        $data = parent::dataCalcul($data);

        // Calcul automatique des critères si une UA est sélectionnée
        if (!empty($data['unite_apprentissage_id'])) {
            $this->enrichDataWithUaCriteriaAndBareme($data);
        }

        return $data;
    }

}
