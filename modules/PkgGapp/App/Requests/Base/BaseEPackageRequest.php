<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseEPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => 'required|max:255',
            'name' => 'required|max:255',
            'description' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'reference.required' => __('validation.required', ['attribute' => __('PkgGapp::EPackage.reference')]),
            'reference.max' => __('validation.referenceMax'),
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::EPackage.name')]),
            'name.max' => __('validation.nameMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::EPackage.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}
