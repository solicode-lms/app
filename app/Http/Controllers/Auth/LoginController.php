<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\SessionStateMiddleware;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Core\Services\ContextState;
use Modules\PkgAutorisation\Models\Role;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
                // Middleware appliqué à toutes les méthodes
        $this->middleware(SessionStateMiddleware::class);
    }

    protected function authenticated(Request $request, $user)
    {
        // // Récupérer le premier rôle de l'utilisateur
        // $role_name = $user->roles->first()->name ?? 'Aucun rôle';

        // if($role_name === Role::FORMATEUR_ROLE){
        //     $contextState = app(ContextState::class);
        //     $formateur = $user->formateur;
        //     $contextState->set("formateur_id",$formateur->id);
        // }
        // // Récupérer l'année de formation de l'utilisateur s'il est apprenant
        // $anneeFormation = optional(optional($user->apprenant)->groupes->first())->anneeFormation->titre ?? 'Non attribuée';

        // // Stocker les informations dans la session
        // Session::put('user_role', $role_name);
        // Session::put('annee_formation', $anneeFormation);
    }
}
