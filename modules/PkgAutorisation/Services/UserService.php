<?php


namespace Modules\PkgAutorisation\Services;

use Illuminate\Support\Facades\Hash;
use Modules\PkgAutorisation\Services\Base\BaseUserService;

/**
 * Classe UserService pour gérer la persistance de l'entité User.
 */
class UserService extends BaseUserService
{
    public function dataCalcul($user)
    {
        // En Cas d'édit
        if(isset($user->id)){
          
        }
      
        return $user;
    }

   /**
     * Initialise le mot de passe de l'utilisateur à "12345678".
     *
     * @param int $userId L'ID de l'utilisateur dont on veut réinitialiser le mot de passe.
     * @return bool True si la mise à jour a réussi, False sinon.
     */
    public function initPassword(int $userId): bool
    {
        // Récupérer l'utilisateur
        $user = $this->find($userId);

        if (!$user) {
            return false; // Retourner false si l'utilisateur n'existe pas
        }

        // Modifier le mot de passe et sauvegarder
        $user->password = Hash::make("12345678");
        $user->must_change_password = true; // Obliger l'utilisateur à changer son mot de passe après connexion

        $value =  $user->save();
        $this->pushServiceMessage("info","Initialisation de mot de passe", "à sa valeur initial : 12345678");
        return $value;
    }
}
