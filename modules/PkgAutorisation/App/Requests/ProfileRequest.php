<?php

namespace Modules\PkgAutorisation\App\Requests;

use Modules\PkgAutorisation\App\Requests\Base\BaseProfileRequest;

class ProfileRequest extends BaseProfileRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
            'old_password' => 'nullable',
        ];
    }


    // TODO : Mettre à jour Gapp
    public function messages(): array
    {
        return [
            'user_id.required' => __('validation.required', ['attribute' => __('PkgAutorisation::Profile.user_id')]),

            'phone.max' => __('validation.max.string', ['attribute' => __('PkgAutorisation::Profile.phone'), 'max' => 255]),
            
            'address.max' => __('validation.max.string', ['attribute' => __('PkgAutorisation::Profile.address'), 'max' => 255]),
            
            'profile_picture.max' => __('validation.max.string', ['attribute' => __('PkgAutorisation::Profile.profile_picture'), 'max' => 255]),
            
            'bio.string' => __('validation.string', ['attribute' => __('PkgAutorisation::Profile.bio')]),

            'password.min' => __('validation.min.string', ['attribute' => __('PkgAutorisation::Profile.password'), 'min' => 8]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('PkgAutorisation::Profile.password')]),

            'old_password.min' => __('validation.min.string', ['attribute' => __('PkgAutorisation::Profile.old_password'), 'min' => 8]),
        ];
    }
}
