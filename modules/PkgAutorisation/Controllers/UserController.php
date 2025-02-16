<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers;

use Illuminate\Http\Request;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutorisation\Controllers\Base\BaseUserController;

class UserController extends BaseUserController
{
    
   
    public function initPassword(Request $request, string $id) {

        $user = $this->userService->initPassword($id);

        if ($request->ajax()) {

            $message = "Le mot de passe a été modifier avec succès à sa valeur initial";
            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('users.index')->with(
            'success',
            "Le mot de passe a été modifier avec succès"
        );

    }
}
