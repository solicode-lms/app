<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseNiveauDifficulteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|max:255',
            'noteMin' => 'required',
            'noteMax' => 'required',
            'formateur_id' => 'required',
            'description' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauDifficulte.nom')]),
            'nom.max' => __('validation.nomMax'),
            'noteMin.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauDifficulte.noteMin')]),
            'noteMin.max' => __('validation.noteMinMax'),
            'noteMax.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauDifficulte.noteMax')]),
            'noteMax.max' => __('validation.noteMaxMax'),
            'formateur_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauDifficulte.formateur_id')]),
            'formateur_id.max' => __('validation.formateur_idMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauDifficulte.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}
