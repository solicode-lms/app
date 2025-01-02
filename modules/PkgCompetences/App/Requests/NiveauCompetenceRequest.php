<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NiveauCompetenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|max:255',
            'description' => 'required',
            'competence_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauCompetence.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauCompetence.description')]),
            'description.max' => __('validation.descriptionMax'),
            'competence_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauCompetence.competence_id')]),
            'competence_id.max' => __('validation.competence_idMax')
        ];
    }
}
