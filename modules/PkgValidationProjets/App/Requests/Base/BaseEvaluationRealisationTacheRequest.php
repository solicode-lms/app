<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgValidationProjets\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgValidationProjets\Models\EvaluationRealisationTache;

class BaseEvaluationRealisationTacheRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Retourne les règles de validation appliquées aux champs de la requête.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'evaluation_realisation_projet_id' => 'nullable|integer',
            'note' => 'required',
            'message' => 'nullable|string',
            'evaluateur_id' => 'required',
            'realisation_tache_id' => 'required'
        ];
    }

    /**
     * Retourne les messages de validation associés aux règles.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'evaluation_realisation_projet_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationTache.evaluation_realisation_projet_id')]),
            'note.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationTache.note')]),
            'message.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationTache.message')]),
            'evaluateur_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationTache.evaluateur_id')]),
            'realisation_tache_id.required' => __('validation.required', ['attribute' => __('PkgValidationProjets::EvaluationRealisationTache.realisation_tache_id')])
        ];
    }

    
}
