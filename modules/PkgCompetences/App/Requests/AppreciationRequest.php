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
            'description' => 'nullable',
            'noteMin' => 'required',
            'noteMax' => 'required',
            'formateur_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::Appreciation.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::Appreciation.description')]),
            'description.max' => __('validation.descriptionMax'),
            'noteMin.required' => __('validation.required', ['attribute' => __('PkgCompetences::Appreciation.noteMin')]),
            'noteMin.max' => __('validation.noteMinMax'),
            'noteMax.required' => __('validation.required', ['attribute' => __('PkgCompetences::Appreciation.noteMax')]),
            'noteMax.max' => __('validation.noteMaxMax'),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::Appreciation.formateur_id')]),
            'formateur_id.max' => __('validation.formateur_idMax')
        ];
    }
}
