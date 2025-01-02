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
            'nature_livrable_id' => 'required',
            'projet_id' => 'required',
            'description' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Livrable.titre')]),
            'titre.max' => __('validation.titreMax'),
            'nature_livrable_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Livrable.nature_livrable_id')]),
            'nature_livrable_id.max' => __('validation.nature_livrable_idMax'),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Livrable.projet_id')]),
            'projet_id.max' => __('validation.projet_idMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Livrable.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}
