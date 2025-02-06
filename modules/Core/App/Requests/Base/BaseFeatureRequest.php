<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseFeatureRequest extends FormRequest
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
            'description' => 'nullable|string',
            'feature_domain_id' => 'required',
            'permissions' => 'nullable|array'
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
            'name.required' => __('validation.required', ['attribute' => __('Core::Feature.name')]),
            'name.max' => __('validation.nameMax'),
            'description.required' => __('validation.required', ['attribute' => __('Core::Feature.description')]),
            'feature_domain_id.required' => __('validation.required', ['attribute' => __('Core::Feature.feature_domain_id')]),
            'permissions.required' => __('validation.required', ['attribute' => __('Core::Feature.permissions')]),
            'permissions.array' => __('validation.array', ['attribute' => __('Core::Feature.permissions')])
        ];
    }
}
