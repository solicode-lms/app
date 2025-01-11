<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseTechnologyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_technology_id' => 'required',
            'description' => 'nullable',
            'nom' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'category_technology_id.required' => __('validation.required', ['attribute' => __('PkgCompetences::Technology.category_technology_id')]),
            'category_technology_id.max' => __('validation.category_technology_idMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::Technology.description')]),
            'description.max' => __('validation.descriptionMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::Technology.nom')]),
            'nom.max' => __('validation.nomMax')
        ];
    }
}
