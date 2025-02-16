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
    public function dataCalcul($profile)
    {
        // En Cas d'édit
        if(isset($profile->id)){
          
        }
      
        return $profile;
    }

 
    
    public function update($id, array $data): ?Model 
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
                // Modifier le mot de passe si l'ancien est correct
                $user->password = Hash::make($data["password"]);
                $user->must_change_password = false; // Désactiver l'obligation de changement après modification
                $user->save();
            }
        }
    
        return $return_value;
    }
    
}
