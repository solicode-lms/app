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
            'Value' => 'nullable',
            'value_boolean' => 'nullable',
            'value_string' => 'nullable|max:255',
            'value_integer' => 'nullable',
            'value_float' => 'nullable',
            'value_date' => 'nullable',
            'value_datetime' => 'nullable',
            'value_enum' => 'nullable|max:255',
            'value_json' => 'nullable',
            'value_text' => 'nullable',
            'e_model_id' => 'nullable',
            'e_data_field_id' => 'nullable',
            'e_metadata_definition_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'Value.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.Value')]),
            'Value.max' => __('validation.ValueMax'),
            'value_boolean.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_boolean')]),
            'value_boolean.max' => __('validation.value_booleanMax'),
            'value_string.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_string')]),
            'value_string.max' => __('validation.value_stringMax'),
            'value_integer.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_integer')]),
            'value_integer.max' => __('validation.value_integerMax'),
            'value_float.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_float')]),
            'value_float.max' => __('validation.value_floatMax'),
            'value_date.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_date')]),
            'value_date.max' => __('validation.value_dateMax'),
            'value_datetime.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_datetime')]),
            'value_datetime.max' => __('validation.value_datetimeMax'),
            'value_enum.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_enum')]),
            'value_enum.max' => __('validation.value_enumMax'),
            'value_json.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_json')]),
            'value_json.max' => __('validation.value_jsonMax'),
            'value_text.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_text')]),
            'value_text.max' => __('validation.value_textMax'),
            'e_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.e_model_id')]),
            'e_model_id.max' => __('validation.e_model_idMax'),
            'e_data_field_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.e_data_field_id')]),
            'e_data_field_id.max' => __('validation.e_data_field_idMax'),
            'e_metadata_definition_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.e_metadata_definition_id')]),
            'e_metadata_definition_id.max' => __('validation.e_metadata_definition_idMax')
        ];
    }
}
