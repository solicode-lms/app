<?php

namespace Modules\PkgEvaluateurs\App\Requests;

use Modules\PkgEvaluateurs\App\Requests\Base\BaseEvaluationRealisationTacheRequest;

class EvaluationRealisationTacheRequest extends BaseEvaluationRealisationTacheRequest
{
    /**
     * Règles de validation de la demande,
     * en fusionnant celles du parent et en ajustant 'note'.
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $id = $this->route('evaluationRealisationTache')
            ?? $this->route('evaluation_realisation_tache')
            ?? $this->route('id')
            ?? request()->route('evaluationRealisationTache')
            ?? request()->route('evaluation_realisation_tache')
            ?? request()->route('id');

        $model = \Modules\PkgEvaluateurs\Models\EvaluationRealisationTache::find($id);

        $realisationTacheId = $this->input('realisation_tache_id')
            ?? request()->input('realisation_tache_id')
            ?? $model?->realisation_tache_id;

        $realisationTache = \Modules\PkgRealisationTache\Models\RealisationTache::find($realisationTacheId);
        $maxNote = $realisationTache?->tache?->note;

      
        $rules['note'] = [
            'nullable',
            'numeric',
            'min:0',
            $maxNote !== null ? 'max:'.$maxNote : '',
        ];

        return $rules;
    }
}
