<?php


namespace Modules\PkgAutorisation\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\PkgAutorisation\Services\Base\BaseProfileService;
use Illuminate\Validation\ValidationException;

/**
 * Classe ProfileService pour gérer la persistance de l'entité Profile.
 */
class ProfileService extends BaseProfileService
{
   

 
    
    public function update($id, array $data)
    {
        $return_value = parent::update($id, $data);
    
        // Récupérer l'objet correspondant à l'ID
        $modelInstance = $this->find($id);
    
        // Vérifier si le modèle existe et possède une relation avec `user`
        if ($modelInstance && isset($modelInstance->user)) {
            $user = $modelInstance->user;
    
            

            // Vérifier si l'ancien mot de passe est fourni et correct avant modification
            if (isset($data['password'])) {
              
                if (!isset($data['old_password']) || !Hash::check($data['old_password'], $user->password)) {
                    throw ValidationException::withMessages([
                        'old_password' => ['L’ancien mot de passe est incorrect.']
                    ]);
                }

                 // Interdiction de "12345678"
                if (trim((string)$data['password']) === '12345678') {
                    throw ValidationException::withMessages([
                        'password' => ['Le nouveau mot de passe ne peut pas être "12345678".']
                    ]);
                }


                // (Optionnel) empêcher de remettre le même que l’actuel
                if (Hash::check($data['password'], $user->password)) {
                    throw ValidationException::withMessages([
                        'password' => ['Le nouveau mot de passe ne doit pas être identique à l’actuel.']
                    ]);
                }

                // Modifier le mot de passe si l'ancien est correct
                $user->password = Hash::make($data["password"]);
                $user->must_change_password = false; // Désactiver l'obligation de changement après modification
                $user->save();
            }
        }
    
        return $return_value;
    }
    
}
