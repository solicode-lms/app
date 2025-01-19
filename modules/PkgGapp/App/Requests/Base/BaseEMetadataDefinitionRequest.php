<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseEMetadataDefinitionRequest extends FormRequest
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
            'groupe' => 'required|max:255',
            'type' => 'required|max:255',
            'scope' => 'required|max:255',
            'description' => 'nullable|max:255',
            'default_value' => 'nullable|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadataDefinition.code')]),
            'code.max' => __('validation.codeMax'),
            'name.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadataDefinition.name')]),
            'name.max' => __('validation.nameMax'),
            'groupe.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadataDefinition.groupe')]),
            'groupe.max' => __('validation.groupeMax'),
            'type.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadataDefinition.type')]),
            'type.max' => __('validation.typeMax'),
            'scope.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadataDefinition.scope')]),
            'scope.max' => __('validation.scopeMax'),
            'description.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadataDefinition.description')]),
            'description.max' => __('validation.descriptionMax'),
            'default_value.required' => __('validation.required', ['attribute' => __('PkgGapp::EMetadataDefinition.default_value')]),
            'default_value.max' => __('validation.default_valueMax')
        ];
    }
}
