<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|max:255',
            'lien' => 'required|max:255',
            'description' => 'nullable',
            'projet_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Resource.nom')]),
            'nom.max' => __('validation.nomMax'),
            'lien.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Resource.lien')]),
            'lien.max' => __('validation.lienMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Resource.description')]),
            'description.max' => __('validation.descriptionMax'),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Resource.projet_id')]),
            'projet_id.max' => __('validation.projet_idMax')
        ];
    }
}
