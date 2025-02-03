<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\App\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class BaseUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'email_verified_at' => 'nullable',
            'password' => 'required|max:255',
            'remember_token' => 'nullable|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('PkgAutorisation::User.name')]),
            'name.max' => __('validation.nameMax'),
            'email.required' => __('validation.required', ['attribute' => __('PkgAutorisation::User.email')]),
            'email.max' => __('validation.emailMax'),
            'email_verified_at.required' => __('validation.required', ['attribute' => __('PkgAutorisation::User.email_verified_at')]),
            'email_verified_at.max' => __('validation.email_verified_atMax'),
            'password.required' => __('validation.required', ['attribute' => __('PkgAutorisation::User.password')]),
            'password.max' => __('validation.passwordMax'),
            'remember_token.required' => __('validation.required', ['attribute' => __('PkgAutorisation::User.remember_token')]),
            'remember_token.max' => __('validation.remember_tokenMax')
        ];
    }
}
