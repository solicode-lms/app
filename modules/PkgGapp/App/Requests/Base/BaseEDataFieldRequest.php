<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgGapp\Models\EDataField;

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
            'name' => 'required|string|max:255',
            'e_model_id' => 'required',
            'data_type' => 'required|string|max:255',
            'default_value' => 'nullable|string|max:255',
            'column_name' => 'required|string|max:255',
            'e_relationship_id' => 'nullable',
            'field_order' => 'required|integer',
            'db_primaryKey' => 'nullable|boolean',
            'db_nullable' => 'nullable|boolean',
            'db_unique' => 'nullable|boolean',
            'calculable' => 'nullable|boolean',
            'calculable_sql' => 'nullable|string',
            'description' => 'nullable|string'
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
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.name')]),
            'name.max' => __('validation.nameMax'),
            'e_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.e_model_id')]),
            'data_type.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.data_type')]),
            'data_type.max' => __('validation.data_typeMax'),
            'default_value.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.default_value')]),
            'default_value.max' => __('validation.default_valueMax'),
            'column_name.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.column_name')]),
            'column_name.max' => __('validation.column_nameMax'),
            'e_relationship_id.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.e_relationship_id')]),
            'field_order.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.field_order')]),
            'db_primaryKey.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.db_primaryKey')]),
            'db_nullable.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.db_nullable')]),
            'db_unique.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.db_unique')]),
            'calculable.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.calculable')]),
            'calculable_sql.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.calculable_sql')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::EDataField.description')])
        ];
    }

}
