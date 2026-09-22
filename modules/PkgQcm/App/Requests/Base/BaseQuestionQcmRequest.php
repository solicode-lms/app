<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgQcm\Models\QuestionQcm;

class BaseQuestionQcmRequest extends FormRequest
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
            'bareme' => 'nullable',
            'qcm_id' => 'required',
            'question_lib_id' => 'required'
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
            'ordre.required' => __('validation.required', ['attribute' => __('PkgQcm::QuestionQcm.ordre')]),
            'bareme.required' => __('validation.required', ['attribute' => __('PkgQcm::QuestionQcm.bareme')]),
            'qcm_id.required' => __('validation.required', ['attribute' => __('PkgQcm::QuestionQcm.qcm_id')]),
            'question_lib_id.required' => __('validation.required', ['attribute' => __('PkgQcm::QuestionQcm.question_lib_id')])
        ];
    }

}
