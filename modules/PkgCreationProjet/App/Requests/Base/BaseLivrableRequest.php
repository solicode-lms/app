<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseLivrableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'nullable',
            'nature_livrable_id' => 'required',
            'projet_id' => 'required',
            'titre' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Livrable.description')]),
            'description.max' => __('validation.descriptionMax'),
            'nature_livrable_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Livrable.nature_livrable_id')]),
            'nature_livrable_id.max' => __('validation.nature_livrable_idMax'),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Livrable.projet_id')]),
            'projet_id.max' => __('validation.projet_idMax'),
            'titre.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Livrable.titre')]),
            'titre.max' => __('validation.titreMax')
        ];
    }
}
