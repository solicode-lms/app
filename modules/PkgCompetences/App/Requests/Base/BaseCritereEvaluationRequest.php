<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCompetences\Models\CritereEvaluation;

class BaseCritereEvaluationRequest extends FormRequest
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
            'ordre' => 'required|integer',
            'intitule' => 'required|string',
            'bareme' => 'required',
            'phase_evaluation_id' => 'required',
            'unite_apprentissage_id' => 'required'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgCompetences::CritereEvaluation.ordre')]),
            'intitule.required' => __('validation.required', ['attribute' => __('PkgCompetences::CritereEvaluation.intitule')]),
            'bareme.required' => __('validation.required', ['attribute' => __('PkgCompetences::CritereEvaluation.bareme')]),
            'phase_evaluation_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::CritereEvaluation.phase_evaluation_id')]),
            'unite_apprentissage_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::CritereEvaluation.unite_apprentissage_id')])
        ];
    }

    
}
