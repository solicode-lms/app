<?php


namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseCritereEvaluationService;

/**
 * Classe CritereEvaluationService pour gérer la persistance de l'entité CritereEvaluation.
 */
class CritereEvaluationService extends BaseCritereEvaluationService
{

    public function __construct()
    {
        parent::__construct();
        $this->ordreGroupColumn = 'unite_apprentissage_id';

    }
}
