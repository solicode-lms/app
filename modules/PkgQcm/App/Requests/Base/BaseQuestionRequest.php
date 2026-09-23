<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgQcm\Models\Question;

class BaseQuestionRequest extends FormRequest
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
            'ordre' => 'nullable|integer',
            'enonce' => 'required|string',
            'type' => 'required|string|max:255',
            'explication' => 'nullable|string',
            'is_actif' => 'required|boolean',
            'unite_apprentissage_id' => 'nullable',
            'bareme' => 'nullable',
            'qcm_id' => 'nullable'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgQcm::Question.ordre')]),
            'enonce.required' => __('validation.required', ['attribute' => __('PkgQcm::Question.enonce')]),
            'type.required' => __('validation.required', ['attribute' => __('PkgQcm::Question.type')]),
            'type.max' => __('validation.typeMax'),
            'explication.required' => __('validation.required', ['attribute' => __('PkgQcm::Question.explication')]),
            'is_actif.required' => __('validation.required', ['attribute' => __('PkgQcm::Question.is_actif')]),
            'unite_apprentissage_id.required' => __('validation.required', ['attribute' => __('PkgQcm::Question.unite_apprentissage_id')]),
            'bareme.required' => __('validation.required', ['attribute' => __('PkgQcm::Question.bareme')]),
            'qcm_id.required' => __('validation.required', ['attribute' => __('PkgQcm::Question.qcm_id')])
        ];
    }

}
