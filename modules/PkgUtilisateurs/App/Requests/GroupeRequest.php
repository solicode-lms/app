<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|max:255',
            'nom' => 'nullable|max:255',
            'description' => 'nullable',
            'filiere_id' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Groupe.code')]),
            'code.max' => __('validation.codeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Groupe.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Groupe.description')]),
            'description.max' => __('validation.descriptionMax'),
            'filiere_id.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Groupe.filiere_id')]),
            'filiere_id.max' => __('validation.filiere_idMax')
        ];
    }
}
