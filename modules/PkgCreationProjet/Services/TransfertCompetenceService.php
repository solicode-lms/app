<?php

namespace Modules\PkgCreationProjet\Services;

use Modules\PkgCompetences\Models\Technology;
use Modules\PkgCreationProjet\Services\Base\BaseTransfertCompetenceService;

/**
 * Classe TransfertCompetenceService pour gérer la persistance de l'entité TransfertCompetence.
 */
class TransfertCompetenceService extends BaseTransfertCompetenceService
{

     protected array $index_with_relations = ['niveauDifficulte','projet'];

   
    public function dataCalcul($transfertCompetence)
    {
        // Récupérer la question et extraire les nouvelles technologies
        $question = strip_tags($transfertCompetence->question); // Supprimer les balises HTML
        $extractedTechnologies = $this->extractTechnologies($question);

        // Récupérer les technologies déjà saisies depuis la relation Eloquent
        $existingTechnologies = $transfertCompetence->technologies->toArray() ?? [];

        // array_values réindex le tableau pouqe que la conversion vers json reste un tableau
        $technologies = array_values(array_unique(array_merge($existingTechnologies, $extractedTechnologies)));

        // Ajouter les technologies fusionnées à l'objet sans interaction avec la base
        $transfertCompetence->setRelation('technologies', collect($technologies));
        

        return $transfertCompetence;
    }

    /**
     * Extraire les technologies mentionnées dans la question.
     *
     * @param string $question
     * @return array
     */
    private function extractTechnologies(string $question): array
{
    // Liste des technologies disponibles dans la base (ID => Nom)
    $allTechnologies = Technology::pluck('id', 'nom')->toArray();

    $matchedTechnologies = [];

    // Vérifier si chaque technologie est mentionnée dans la question en tant que mot distinct
    foreach ($allTechnologies as $nom => $id) {
        // Utiliser une regex insensible à la casse (`i`) et qui détecte uniquement des mots complets (`\b`)
        $pattern = '/\b' . preg_quote($nom, '/') . '\b/i';

        if (preg_match($pattern, $question)) {
            $matchedTechnologies[] = (string) $id; // Ajouter uniquement l'ID
        }
    }

    return $matchedTechnologies;
}


}
