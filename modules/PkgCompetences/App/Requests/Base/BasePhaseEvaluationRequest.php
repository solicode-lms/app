<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCompetences\Models\PhaseEvaluation;

class BasePhaseEvaluationRequest extends FormRequest
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
            'code' => 'required|string|max:255',
            'libelle' => 'required|string|max:255',
            'coefficient' => 'required',
            'description' => 'nullable|string'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgCompetences::PhaseEvaluation.ordre')]),
            'code.required' => __('validation.required', ['attribute' => __('PkgCompetences::PhaseEvaluation.code')]),
            'code.max' => __('validation.codeMax'),
            'libelle.required' => __('validation.required', ['attribute' => __('PkgCompetences::PhaseEvaluation.libelle')]),
            'libelle.max' => __('validation.libelleMax'),
            'coefficient.required' => __('validation.required', ['attribute' => __('PkgCompetences::PhaseEvaluation.coefficient')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::PhaseEvaluation.description')])
        ];
    }

}
