<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseDataFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'i_model_id' => 'required',
            'field_type_id' => 'required',
            'description' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::DataField.name')]),
            'name.max' => __('validation.nameMax'),
            'i_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::DataField.i_model_id')]),
            'i_model_id.max' => __('validation.i_model_idMax'),
            'field_type_id.required' => __('validation.required', ['attribute' => __('PkgGapp::DataField.field_type_id')]),
            'field_type_id.max' => __('validation.field_type_idMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::DataField.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}
