<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseEModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'icon' => 'nullable|max:255',
            'description' => 'nullable',
            'e_package_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.name')]),
            'name.max' => __('validation.nameMax'),
            'icon.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.icon')]),
            'icon.max' => __('validation.iconMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.description')]),
            'description.max' => __('validation.descriptionMax'),
            'e_package_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EModel.e_package_id')]),
            'e_package_id.max' => __('validation.e_package_idMax')
        ];
    }
}
