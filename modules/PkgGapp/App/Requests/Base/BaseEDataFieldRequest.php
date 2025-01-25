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
            'column_name' => 'required|max:255',
            'data_type' => 'required|max:255',
            'db_nullable' => 'required',
            'db_primaryKey' => 'required',
            'db_unique' => 'required',
            'default_value' => 'nullable|max:255',
            'description' => 'nullable',
            'e_model_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.name')]),
            'name.max' => __('validation.nameMax'),
            'column_name.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.column_name')]),
            'column_name.max' => __('validation.column_nameMax'),
            'data_type.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.data_type')]),
            'data_type.max' => __('validation.data_typeMax'),
            'db_nullable.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.db_nullable')]),
            'db_nullable.max' => __('validation.db_nullableMax'),
            'db_primaryKey.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.db_primaryKey')]),
            'db_primaryKey.max' => __('validation.db_primaryKeyMax'),
            'db_unique.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.db_unique')]),
            'db_unique.max' => __('validation.db_uniqueMax'),
            'default_value.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.default_value')]),
            'default_value.max' => __('validation.default_valueMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.description')]),
            'description.max' => __('validation.descriptionMax'),
            'e_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.e_model_id')]),
            'e_model_id.max' => __('validation.e_model_idMax')
        ];
    }
}
