<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgGapp\Models\ERelationship;

class BaseERelationshipRequest extends FormRequest
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
            'type' => 'required|string|max:255',
            'source_e_model_id' => 'required',
            'target_e_model_id' => 'required',
            'cascade_on_delete' => 'required|boolean',
            'is_cascade' => 'required|boolean',
            'description' => 'nullable|string',
            'column_name' => 'nullable|string|max:255',
            'referenced_table' => 'nullable|string|max:255',
            'referenced_column' => 'nullable|string|max:255',
            'through' => 'nullable|string|max:255',
            'with_column' => 'nullable|string|max:255',
            'morph_name' => 'nullable|string|max:255'
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
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.name')]),
            'name.max' => __('validation.nameMax'),
            'type.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.type')]),
            'type.max' => __('validation.typeMax'),
            'source_e_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.source_e_model_id')]),
            'target_e_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.target_e_model_id')]),
            'cascade_on_delete.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.cascade_on_delete')]),
            'is_cascade.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.is_cascade')]),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.description')]),
            'column_name.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.column_name')]),
            'column_name.max' => __('validation.column_nameMax'),
            'referenced_table.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.referenced_table')]),
            'referenced_table.max' => __('validation.referenced_tableMax'),
            'referenced_column.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.referenced_column')]),
            'referenced_column.max' => __('validation.referenced_columnMax'),
            'through.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.through')]),
            'through.max' => __('validation.throughMax'),
            'with_column.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.with_column')]),
            'with_column.max' => __('validation.with_columnMax'),
            'morph_name.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.morph_name')]),
            'morph_name.max' => __('validation.morph_nameMax')
        ];
    }

}
