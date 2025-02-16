<?php


namespace Modules\PkgAutorisation\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Controllers\Base\BaseProfileController;

class ProfileController extends BaseProfileController
{
    public function index(Request $request) {

        if (Auth::check() && Auth::user()->must_change_password) {
            session()->flash(key: 'warning', value: 'Vous devez changer votre mot de passe avant d’accéder à l’application.');
        }
        return parent::index($request);
    }

}
