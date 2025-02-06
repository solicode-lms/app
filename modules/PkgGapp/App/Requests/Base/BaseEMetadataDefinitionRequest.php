<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseEMetadataDefinitionRequest extends FormRequest
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
            'groupe' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'scope' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'default_value' => 'nullable|string|max:255'
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
