<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Permission;

class BasePermissionRequest extends FormRequest
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
            'guard_name' => 'required|string|max:255',
            'controller_id' => 'nullable',
            'features' => 'nullable|array',
            'roles' => 'nullable|array'
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
            'name.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Permission.name')]),
            'name.max' => __('validation.nameMax'),
            'guard_name.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Permission.guard_name')]),
            'guard_name.max' => __('validation.guard_nameMax'),
            'controller_id.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Permission.controller_id')]),
            'features.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Permission.features')]),
            'features.array' => __('validation.array', ['attribute' => __('PkgAutorisation::Permission.features')]),
            'roles.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Permission.roles')]),
            'roles.array' => __('validation.array', ['attribute' => __('PkgAutorisation::Permission.roles')])
        ];
    }

    
}
