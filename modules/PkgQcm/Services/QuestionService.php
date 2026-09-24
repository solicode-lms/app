<?php



namespace Modules\PkgQcm\Services;
use Modules\PkgQcm\Services\Base\BaseQuestionService;
use Modules\PkgQcm\Models\Enums\QuestionTypeEnum;
use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\PkgQcm\Models\Question;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Classe QuestionService pour gérer la persistance de l'entité Question.
 */
class QuestionService extends BaseQuestionService
{
    /**
     * Surcharge du hook de création pour calculer automatiquement l'ordre
     */
    protected function beforeCreateRules(array &$data): void
    {
        if (empty($data['ordre']) && !empty($data['qcm_id'])) {
            $data['ordre'] = Question::where('qcm_id', $data['qcm_id'])->max('ordre') + 1;
        }
    }
    /**
     * Initialise une nouvelle instance avec des valeurs par défaut pour le formulaire.
     */
    public function createInstance(array $data = [])
    {
        if (!isset($data['type'])) {
            $data['type'] = QuestionTypeEnum::CHOIX_UNIQUE->value;
        }
        if (!isset($data['bareme'])) {
            $data['bareme'] = 1;
        }
        if (!isset($data['is_actif'])) {
            $data['is_actif'] = true;
        }
        return parent::createInstance($data);
    }

    /**
     * Personnalisation des filtres de la liste (index).
     */
    public function initFieldsFilterable()
    {
        $scopeVariables = $this->viewState->getScopeVariables('question');
        $this->fieldsFilterable = [];

        if (!array_key_exists('qcm_id', $scopeVariables)) {
            $qcmService = new \Modules\PkgQcm\Services\QcmService();
            $qcmIds = $this->getAvailableFilterValues('qcm_id');
            $qcms = $qcmService->getByIds($qcmIds);

            $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                __("PkgQcm::qcm.plural"), 
                'qcm_id', 
                \Modules\PkgQcm\Models\Qcm::class, 
                'titre',
                $qcms
            );
        }

        if (!array_key_exists('unite_apprentissage_id', $scopeVariables)) {
            $uniteApprentissageService = new \Modules\PkgCompetences\Services\UniteApprentissageService();
            $uniteApprentissageIds = $this->getAvailableFilterValues('unite_apprentissage_id');
            $uniteApprentissages = $uniteApprentissageService->getByIds($uniteApprentissageIds);

            $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                __("PkgCompetences::uniteApprentissage.plural"), 
                'unite_apprentissage_id', 
                \Modules\PkgCompetences\Models\UniteApprentissage::class, 
                'nom', // Utilisation de 'nom' pour un affichage plus clair que 'code'
                $uniteApprentissages
            );
        }
    }

    /**
     * Importe des questions à partir d'un JSON généré par IA.
     *
     * @param string $jsonPayload
     * @param int|null $qcmId Optionnel, ID du QCM auquel attacher les questions
     * @return int Nombre de questions importées
     * @throws Exception
     */
    public function importFromJson(string $jsonPayload, ?int $qcmId = null): int
    {
        $data = json_decode($jsonPayload, true);
        if (!is_array($data)) {
            throw new Exception("Le format JSON est invalide.");
        }

        $count = 0;
        DB::transaction(function () use ($data, &$count, $qcmId) {
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
                    'type' => QuestionTypeEnum::CHOIX_UNIQUE, // Utilisation de l'Enum
                    'bareme' => $item['points'] ?? 1,
                    'is_actif' => true,
                    'unite_apprentissage_id' => $ua_id,
                    'qcm_id' => $qcmId,
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
