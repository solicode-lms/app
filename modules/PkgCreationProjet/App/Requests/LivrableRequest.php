<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LivrableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|max:255',
            'description' => 'nullable|max:255',
            'projet_id' => 'required',
            'nature_livrable_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => __('validation.required', ['attribute' => __('PkgBlog::category.titre')]),
            'titre.max' => __('validation.titreMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgBlog::category.description')]),
            'description.max' => __('validation.descriptionMax'),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.projet_id')]),
            'projet_id.max' => __('validation.projet_idMax'),
            'nature_livrable_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.nature_livrable_id')]),
            'nature_livrable_id.max' => __('validation.nature_livrable_idMax')
        ];
    }
}
