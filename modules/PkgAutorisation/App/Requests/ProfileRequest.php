<?php
// add password pour changement de mot de passe


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
        ];
    }
}
