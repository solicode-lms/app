<?php


namespace Modules\PkgAutorisation\Models;
use Modules\PkgAutorisation\Models\Base\BaseProfile;

class Profile extends BaseProfile
{
    public function __toString()
    {
        return $this->user->email ?? "";
    }

    public function generateReference(): string
    {
        return $this->user->reference ;
    }
}
