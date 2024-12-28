<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompetenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|max:255',
            'nom' => 'required|max:255',
            'description' => 'required|max:255',
            'module_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.code')]),
            'code.max' => __('validation.codeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.description')]),
            'description.max' => __('validation.descriptionMax'),
            'module_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::Competence.module_id')]),
            'module_id.max' => __('validation.module_idMax')
        ];
    }
}
