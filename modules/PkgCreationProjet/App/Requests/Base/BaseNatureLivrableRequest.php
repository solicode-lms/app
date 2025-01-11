<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseNatureLivrableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'nullable',
            'nom' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::NatureLivrable.description')]),
            'description.max' => __('validation.descriptionMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgCreationProjet::NatureLivrable.nom')]),
            'nom.max' => __('validation.nomMax')
        ];
    }
}
