<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'nullable',
            'lien' => 'required|max:255',
            'nom' => 'required|max:255',
            'projet_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Resource.description')]),
            'description.max' => __('validation.descriptionMax'),
            'lien.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Resource.lien')]),
            'lien.max' => __('validation.lienMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Resource.nom')]),
            'nom.max' => __('validation.nomMax'),
            'projet_id.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::Resource.projet_id')]),
            'projet_id.max' => __('validation.projet_idMax')
        ];
    }
}
