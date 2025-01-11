<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseEMetadatumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'e_metadata_definition_id' => 'required',
            'object_id' => 'required',
            'object_type' => 'required|max:255',
            'value_boolean' => 'nullable',
            'value_int' => 'nullable',
            'value_object' => 'nullable',
            'value_string' => 'nullable|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'e_metadata_definition_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.e_metadata_definition_id')]),
            'e_metadata_definition_id.max' => __('validation.e_metadata_definition_idMax'),
            'object_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.object_id')]),
            'object_id.max' => __('validation.object_idMax'),
            'object_type.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.object_type')]),
            'object_type.max' => __('validation.object_typeMax'),
            'value_boolean.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_boolean')]),
            'value_boolean.max' => __('validation.value_booleanMax'),
            'value_int.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_int')]),
            'value_int.max' => __('validation.value_intMax'),
            'value_object.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_object')]),
            'value_object.max' => __('validation.value_objectMax'),
            'value_string.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_string')]),
            'value_string.max' => __('validation.value_stringMax')
        ];
    }
}
