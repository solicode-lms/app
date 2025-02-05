<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseEMetadatumRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Retourne les règles de validation appliquées aux champs de la requête.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'Value' => 'required',
            'value_boolean' => 'nullable|integer',
            'value_string' => 'nullable|string|max:255',
            'value_integer' => 'nullable|integer',
            'value_float' => 'nullable',
            'value_date' => 'nullable',
            'value_datetime' => 'nullable',
            'value_enum' => 'nullable|string|max:255',
            'value_json' => 'nullable',
            'value_text' => 'nullable|string',
            'e_model_id' => 'nullable',
            'e_data_field_id' => 'nullable',
            'e_metadata_definition_id' => 'required'
        ];
    }

    /**
     * Retourne les messages de validation associés aux règles.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'Value.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.Value')]),
            'value_boolean.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_boolean')]),
            'value_string.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_string')]),
            'value_string.max' => __('validation.value_stringMax'),
            'value_integer.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_integer')]),
            'value_float.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_float')]),
            'value_date.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_date')]),
            'value_datetime.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_datetime')]),
            'value_enum.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_enum')]),
            'value_enum.max' => __('validation.value_enumMax'),
            'value_json.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_json')]),
            'value_text.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.value_text')]),
            'e_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.e_model_id')]),
            'e_data_field_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.e_data_field_id')]),
            'e_metadata_definition_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadatum.e_metadata_definition_id')])
        ];
    }
}
