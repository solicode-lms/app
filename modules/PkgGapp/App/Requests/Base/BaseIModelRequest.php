<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseIModelRequest extends FormRequest
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
            'i_package_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::IModel.name')]),
            'name.max' => __('validation.nameMax'),
            'icon.required' => __('validation.required', ['attribute' => __('PkgGapp::IModel.icon')]),
            'icon.max' => __('validation.iconMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::IModel.description')]),
            'description.max' => __('validation.descriptionMax'),
            'i_package_id.required' => __('validation.required', ['attribute' => __('PkgGapp::IModel.i_package_id')]),
            'i_package_id.max' => __('validation.i_package_idMax')
        ];
    }
}
