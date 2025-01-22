<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseERelationshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|max:255',
            'name' => 'required|max:255',
            'type' => 'required|max:255',
            'source_model_id' => 'required',
            'target_model_id' => 'required',
            'source_model_code' => 'required|max:255',
            'target_model_code' => 'required|max:255',
            'cascade_on_delete' => 'required',
            'is_cascade' => 'required',
            'description' => 'nullable',
            'column_name' => 'nullable|max:255',
            'referenced_table' => 'nullable|max:255',
            'referenced_column' => 'nullable|max:255',
            'through' => 'nullable|max:255',
            'with_column' => 'nullable|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.code')]),
            'code.max' => __('validation.codeMax'),
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.name')]),
            'name.max' => __('validation.nameMax'),
            'type.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.type')]),
            'type.max' => __('validation.typeMax'),
            'source_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.source_model_id')]),
            'source_model_id.max' => __('validation.source_model_idMax'),
            'target_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.target_model_id')]),
            'target_model_id.max' => __('validation.target_model_idMax'),
            'source_model_code.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.source_model_code')]),
            'source_model_code.max' => __('validation.source_model_codeMax'),
            'target_model_code.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.target_model_code')]),
            'target_model_code.max' => __('validation.target_model_codeMax'),
            'cascade_on_delete.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.cascade_on_delete')]),
            'cascade_on_delete.max' => __('validation.cascade_on_deleteMax'),
            'is_cascade.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.is_cascade')]),
            'is_cascade.max' => __('validation.is_cascadeMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.description')]),
            'description.max' => __('validation.descriptionMax'),
            'column_name.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.column_name')]),
            'column_name.max' => __('validation.column_nameMax'),
            'referenced_table.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.referenced_table')]),
            'referenced_table.max' => __('validation.referenced_tableMax'),
            'referenced_column.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.referenced_column')]),
            'referenced_column.max' => __('validation.referenced_columnMax'),
            'through.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.through')]),
            'through.max' => __('validation.throughMax'),
            'with_column.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.with_column')]),
            'with_column.max' => __('validation.with_columnMax')
        ];
    }
}
