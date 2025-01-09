<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseMetadataTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'code' => 'required|max:255',
            'type' => 'required|max:255',
            'scope' => 'required|max:255',
            'description' => 'nullable|max:255',
            'is_required' => 'required',
            'default_value' => 'nullable|max:255',
            'validation_rules' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::MetadataType.name')]),
            'name.max' => __('validation.nameMax'),
            'code.required' => __('validation.required', ['attribute' => __('PkgGapp::MetadataType.code')]),
            'code.max' => __('validation.codeMax'),
            'type.required' => __('validation.required', ['attribute' => __('PkgGapp::MetadataType.type')]),
            'type.max' => __('validation.typeMax'),
            'scope.required' => __('validation.required', ['attribute' => __('PkgGapp::MetadataType.scope')]),
            'scope.max' => __('validation.scopeMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::MetadataType.description')]),
            'description.max' => __('validation.descriptionMax'),
            'is_required.required' => __('validation.required', ['attribute' => __('PkgGapp::MetadataType.is_required')]),
            'is_required.max' => __('validation.is_requiredMax'),
            'default_value.required' => __('validation.required', ['attribute' => __('PkgGapp::MetadataType.default_value')]),
            'default_value.max' => __('validation.default_valueMax'),
            'validation_rules.required' => __('validation.required', ['attribute' => __('PkgGapp::MetadataType.validation_rules')]),
            'validation_rules.max' => __('validation.validation_rulesMax')
        ];
    }
}
