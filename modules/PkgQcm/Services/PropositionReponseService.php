<?php



namespace Modules\PkgQcm\Services;
use Modules\PkgQcm\Services\Base\BasePropositionReponseService;
use Modules\PkgQcm\Models\PropositionReponse;

/**
 * Classe PropositionReponseService pour gérer la persistance de l'entité PropositionReponse.
 */
class PropositionReponseService extends BasePropositionReponseService
{
    /**
     * Surcharge du hook de création pour calculer automatiquement l'ordre
     */
    protected function beforeCreateRules(array &$data): void
    {
        if (empty($data['ordre']) && !empty($data['question_id'])) {
            $data['ordre'] = PropositionReponse::where('question_id', $data['question_id'])->max('ordre') + 1;
        }
    }
}
