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
            'source_model_id' => 'required',
            'target_model_id' => 'required',
            'type' => 'required|max:255',
            'source_field' => 'required|max:255',
            'target_field' => 'required|max:255',
            'cascade_on_delete' => 'required',
            'description' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'source_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.source_model_id')]),
            'source_model_id.max' => __('validation.source_model_idMax'),
            'target_model_id.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.target_model_id')]),
            'target_model_id.max' => __('validation.target_model_idMax'),
            'type.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.type')]),
            'type.max' => __('validation.typeMax'),
            'source_field.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.source_field')]),
            'source_field.max' => __('validation.source_fieldMax'),
            'target_field.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.target_field')]),
            'target_field.max' => __('validation.target_fieldMax'),
            'cascade_on_delete.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.cascade_on_delete')]),
            'cascade_on_delete.max' => __('validation.cascade_on_deleteMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::ERelationship.description')]),
            'description.max' => __('validation.descriptionMax')
        ];
    }
}
