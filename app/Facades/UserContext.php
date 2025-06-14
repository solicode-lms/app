<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class UserContext extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Modules\PkgAutorisation\Services\UserService::class;
    }
}
