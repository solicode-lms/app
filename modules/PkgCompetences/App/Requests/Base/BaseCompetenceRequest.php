<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseCompetenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|max:255',
            'description' => 'nullable',
            'module_id' => 'required',
            'nom' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.code')]),
            'code.max' => __('validation.codeMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.description')]),
            'description.max' => __('validation.descriptionMax'),
            'module_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.module_id')]),
            'module_id.max' => __('validation.module_idMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.nom')]),
            'nom.max' => __('validation.nomMax')
        ];
    }
}
