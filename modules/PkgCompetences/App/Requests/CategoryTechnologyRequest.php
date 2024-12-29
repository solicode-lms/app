<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryTechnologyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|max:255',
            'description' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('validation.required', ['attribute' => __('PkgCompetences::CategoryTechnology.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCompetences::CategoryTechnology.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}
