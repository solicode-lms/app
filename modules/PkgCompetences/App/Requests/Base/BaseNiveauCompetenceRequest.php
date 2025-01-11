<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseNiveauCompetenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'competence_id' => 'required',
            'description' => 'nullable',
            'nom' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'competence_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauCompetence.competence_id')]),
            'competence_id.max' => __('validation.competence_idMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauCompetence.description')]),
            'description.max' => __('validation.descriptionMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::NiveauCompetence.nom')]),
            'nom.max' => __('validation.nomMax')
        ];
    }
}
