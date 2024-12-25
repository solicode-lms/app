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
            'nom' => 'required|max:255',
            'description' => 'required|max:255',
            'filiere_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => __('validation.required', ['attribute' => __('PkgBlog::category.code')]),
            'code.max' => __('validation.codeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgBlog::category.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgBlog::category.description')]),
            'description.max' => __('validation.descriptionMax'),
            'filiere_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.filiere_id')]),
            'filiere_id.max' => __('validation.filiere_idMax')
        ];
    }
}
