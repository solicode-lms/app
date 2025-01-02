<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TechnologyRequest extends FormRequest
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
            'category_technology_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::Technology.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::Technology.description')]),
            'description.max' => __('validation.descriptionMax'),
            'category_technology_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::Technology.category_technology_id')]),
            'category_technology_id.max' => __('validation.category_technology_idMax')
        ];
    }
}
