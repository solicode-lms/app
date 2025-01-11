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
            'description' => 'nullable',
            'e_model_id' => 'required',
            'name' => 'required|max:255',
            'type' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.description')]),
            'description.max' => __('validation.descriptionMax'),
            'e_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.e_model_id')]),
            'e_model_id.max' => __('validation.e_model_idMax'),
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.name')]),
            'name.max' => __('validation.nameMax'),
            'type.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.type')]),
            'type.max' => __('validation.typeMax')
        ];
    }
}
