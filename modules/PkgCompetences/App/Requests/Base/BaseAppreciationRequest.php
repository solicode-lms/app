<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseAppreciationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'nullable',
            'formateur_id' => 'required',
            'nom' => 'required|max:255',
            'noteMax' => 'required',
            'noteMin' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::Appreciation.description')]),
            'description.max' => __('validation.descriptionMax'),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::Appreciation.formateur_id')]),
            'formateur_id.max' => __('validation.formateur_idMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::Appreciation.nom')]),
            'nom.max' => __('validation.nomMax'),
            'noteMax.required' => __('validation.required', ['attribute' => __('PkgCompetences::Appreciation.noteMax')]),
            'noteMax.max' => __('validation.noteMaxMax'),
            'noteMin.required' => __('validation.required', ['attribute' => __('PkgCompetences::Appreciation.noteMin')]),
            'noteMin.max' => __('validation.noteMinMax')
        ];
    }
}
