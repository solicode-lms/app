<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransfertCompetenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'nullable|max:255',
            'projet_id' => 'required',
            'competence_id' => 'required',
            'appreciation_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => __('validation.required', ['attribute' => __('PkgBlog::category.description')]),
            'description.max' => __('validation.descriptionMax'),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.projet_id')]),
            'projet_id.max' => __('validation.projet_idMax'),
            'competence_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.competence_id')]),
            'competence_id.max' => __('validation.competence_idMax'),
            'appreciation_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.appreciation_id')]),
            'appreciation_id.max' => __('validation.appreciation_idMax')
        ];
    }
}
