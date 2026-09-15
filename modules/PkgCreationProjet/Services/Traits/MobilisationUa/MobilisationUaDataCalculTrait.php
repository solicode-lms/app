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
            
            // Appliquer les valeurs par défaut pour l'affichage (UX) si l'UA n'a pas de barème défini
            $totalBareme = (float)($data['bareme_evaluation_prototype'] ?? 0) + (float)($data['bareme_evaluation_projet'] ?? 0);
            if ($totalBareme <= 0) {
                $data['bareme_evaluation_prototype'] = 4;
                $data['bareme_evaluation_projet'] = 2;
            }
        }

        return $data;
    }

}
