<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseEDataFieldRequest extends FormRequest
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
            'order' => 'nullable',
            'name' => 'required|string|max:255',
            'column_name' => 'required|string|max:255',
            'field_order' => 'required|integer',
            'db_nullable' => 'required|boolean',
            'db_primaryKey' => 'required|boolean',
            'db_unique' => 'required|boolean',
            'default_value' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'e_model_id' => 'required',
            'e_relationship_id' => 'nullable',
            'data_type' => 'required|string|max:255'
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
            'order.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.order')]),
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.name')]),
            'name.max' => __('validation.nameMax'),
            'column_name.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.column_name')]),
            'column_name.max' => __('validation.column_nameMax'),
            'field_order.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.field_order')]),
            'db_nullable.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.db_nullable')]),
            'db_primaryKey.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.db_primaryKey')]),
            'db_unique.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.db_unique')]),
            'default_value.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.default_value')]),
            'default_value.max' => __('validation.default_valueMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.description')]),
            'e_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.e_model_id')]),
            'e_relationship_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.e_relationship_id')]),
            'data_type.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.data_type')]),
            'data_type.max' => __('validation.data_typeMax')
        ];
    }
}
