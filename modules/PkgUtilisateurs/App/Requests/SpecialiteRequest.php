<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecialiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|max:255',
            'description' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Specialite.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Specialite.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}
