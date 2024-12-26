<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppreciationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|max:255',
            'description' => 'nullable|max:255',
            'noteMin' => 'required',
            'noteMax' => 'required',
            'niveau_competence_id' => 'required',
            'formateur_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('validation.required', ['attribute' => __('PkgBlog::category.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgBlog::category.description')]),
            'description.max' => __('validation.descriptionMax'),
            'noteMin.required' => __('validation.required', ['attribute' => __('PkgBlog::category.noteMin')]),
            'noteMin.max' => __('validation.noteMinMax'),
            'noteMax.required' => __('validation.required', ['attribute' => __('PkgBlog::category.noteMax')]),
            'noteMax.max' => __('validation.noteMaxMax'),
            'niveau_competence_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.niveau_competence_id')]),
            'niveau_competence_id.max' => __('validation.niveau_competence_idMax'),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.formateur_id')]),
            'formateur_id.max' => __('validation.formateur_idMax')
        ];
    }
}
