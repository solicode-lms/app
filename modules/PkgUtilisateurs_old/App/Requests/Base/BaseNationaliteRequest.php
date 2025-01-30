<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseNationaliteRequest extends FormRequest
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
            'description' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Nationalite.code')]),
            'code.max' => __('validation.codeMax'),
            'nom.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Nationalite.nom')]),
            'nom.max' => __('validation.nomMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgUtilisateurs::Nationalite.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}
