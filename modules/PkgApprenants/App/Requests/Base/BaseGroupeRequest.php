<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseGroupeRequest extends FormRequest
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
            'filiere_id' => 'nullable',
            'annee_formation_id' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => __('validation.required', ['attribute' => __('PkgApprenants::Groupe.code')]),
            'code.max' => __('validation.codeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgApprenants::Groupe.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgApprenants::Groupe.description')]),
            'description.max' => __('validation.descriptionMax'),
            'filiere_id.required' => __('validation.required', ['attribute' => __('PkgApprenants::Groupe.filiere_id')]),
            'filiere_id.max' => __('validation.filiere_idMax'),
            'annee_formation_id.required' => __('validation.required', ['attribute' => __('PkgApprenants::Groupe.annee_formation_id')]),
            'annee_formation_id.max' => __('validation.annee_formation_idMax')
        ];
    }
}
