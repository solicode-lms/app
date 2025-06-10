<?php

namespace Modules\PkgValidationProjets\Models;
use Modules\PkgValidationProjets\Models\Base\BaseEvaluationRealisationTache;

class EvaluationRealisationTache extends BaseEvaluationRealisationTache
{

        /**
     * Barème max pour la note = note de la tâche liée.
     */
    public function getMaxNote(): float|null
    {
        return $this->realisationTache?->tache?->note !== null
            ? round($this->realisationTache->tache->note, 2)
            : null;
    }

}
