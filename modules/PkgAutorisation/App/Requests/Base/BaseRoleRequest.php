<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Role;

class BaseRoleRequest extends FormRequest
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
            'permissions' => 'nullable|array',
            'widgets' => 'nullable|array',
            'users' => 'nullable|array'
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
            'name.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Role.name')]),
            'name.max' => __('validation.nameMax'),
            'guard_name.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Role.guard_name')]),
            'guard_name.max' => __('validation.guard_nameMax'),
            'permissions.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Role.permissions')]),
            'permissions.array' => __('validation.array', ['attribute' => __('PkgAutorisation::Role.permissions')]),
            'widgets.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Role.widgets')]),
            'widgets.array' => __('validation.array', ['attribute' => __('PkgAutorisation::Role.widgets')]),
            'users.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Role.users')]),
            'users.array' => __('validation.array', ['attribute' => __('PkgAutorisation::Role.users')])
        ];
    }

    
}
