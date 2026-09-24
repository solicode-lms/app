<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgQcm\Services;
use Modules\PkgQcm\Services\Base\BaseQuestionService;

/**
 * Classe QuestionService pour gérer la persistance de l'entité Question.
 */
use Illuminate\Support\Facades\DB;
use Modules\PkgCompetences\Models\UniteApprentissage;
use Exception;

class QuestionService extends BaseQuestionService
{
    /**
     * Importe des questions à partir d'un JSON généré par IA.
     *
     * @param string $jsonPayload
     * @return int Nombre de questions importées
     * @throws Exception
     */
    public function importFromJson(string $jsonPayload): int
    {
        $data = json_decode($jsonPayload, true);
        if (!is_array($data)) {
            throw new Exception("Le format JSON est invalide.");
        }

        $count = 0;
        DB::transaction(function () use ($data, &$count) {
            foreach ($data as $item) {
                if (!isset($item['question']) || !isset($item['reponses']) || !isset($item['bonneReponse'])) {
                    continue; // Ignorer les éléments mal formés
                }

                $ua_id = null;
                if (isset($item['unite_apprentissage_code'])) {
                    $ua = UniteApprentissage::where('code', $item['unite_apprentissage_code'])->first();
                    if ($ua) {
                        $ua_id = $ua->id;
                    }
                }

                // Créer la question
                $question = $this->create([
                    'enonce' => $item['question'],
                    'type' => 'Choix unique', // Par défaut
                    'bareme' => $item['points'] ?? 1,
                    'is_actif' => true,
                    'unite_apprentissage_id' => $ua_id,
                ]);

                // Créer les propositions de réponse
                foreach ($item['reponses'] as $index => $reponseTexte) {
                    $is_correct = (($index + 1) == $item['bonneReponse']);
                    
                    $question->propositionReponses()->create([
                        'libelle' => $reponseTexte,
                        'is_correcte' => $is_correct,
                    ]);
                }
                
                $count++;
            }
        });

        return $count;
    }
}
