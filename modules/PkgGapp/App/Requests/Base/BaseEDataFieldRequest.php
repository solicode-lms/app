<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseEDataFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'type' => 'required|max:255',
            'e_model_id' => 'required',
            'description' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.name')]),
            'name.max' => __('validation.nameMax'),
            'type.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.type')]),
            'type.max' => __('validation.typeMax'),
            'e_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.e_model_id')]),
            'e_model_id.max' => __('validation.e_model_idMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}
