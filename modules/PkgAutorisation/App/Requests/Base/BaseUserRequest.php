<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\User;

class BaseUserRequest extends FormRequest
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
            'email' => 'required|string|max:255',
            'email_verified_at' => 'nullable',
            'password' => 'required|string|max:255',
            'must_change_password' => 'required|boolean',
            'remember_token' => 'nullable|string|max:255',
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
            'name.required' => __('validation.required', ['attribute' => __('PkgAutorisation::User.name')]),
            'name.max' => __('validation.nameMax'),
            'email.required' => __('validation.required', ['attribute' => __('PkgAutorisation::User.email')]),
            'email.max' => __('validation.emailMax'),
            'email_verified_at.required' => __('validation.required', ['attribute' => __('PkgAutorisation::User.email_verified_at')]),
            'password.required' => __('validation.required', ['attribute' => __('PkgAutorisation::User.password')]),
            'password.max' => __('validation.passwordMax'),
            'must_change_password.required' => __('validation.required', ['attribute' => __('PkgAutorisation::User.must_change_password')]),
            'remember_token.required' => __('validation.required', ['attribute' => __('PkgAutorisation::User.remember_token')]),
            'remember_token.max' => __('validation.remember_tokenMax'),
            'roles.required' => __('validation.required', ['attribute' => __('PkgAutorisation::User.roles')]),
            'roles.array' => __('validation.array', ['attribute' => __('PkgAutorisation::User.roles')])
        ];
    }

    
}
