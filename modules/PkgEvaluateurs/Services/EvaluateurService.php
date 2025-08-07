<?php


namespace Modules\PkgEvaluateurs\Services;

use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgEvaluateurs\Services\Base\BaseEvaluateurService;

/**
 * Classe EvaluateurService pour gérer la persistance de l'entité Evaluateur.
 */
class EvaluateurService extends BaseEvaluateurService
{
   





 /**
     * Crée un évaluateur et initialise ses dépendances.
     *
     * @param array $data
     * @return mixed
     */
    public function create($data)
    {
        $evaluateur = parent::create($data);

     
        // Création d'un utilisateur pour l’évaluateur si non existant
        if (is_null($evaluateur->user_id)) {
            $userService = new UserService();
            $userData = [
                'name' => strtoupper($evaluateur->nom) . " " . ucfirst($evaluateur->prenom),
                'email' => $evaluateur->email ,
                'password' => bcrypt("12345678"), // Hash du mot de passe pour sécurité
            ];

            $user = $userService->create($userData);

            if ($user) {
                $user->assignRole(Role::EVALUATEUR_ROLE);
                $evaluateur->user_id = $user->id;
                $evaluateur->save();
            }
        }

        return $evaluateur;
    }


    public function initPassword(int $evaluateurId)
    {
        $evaluateur = $this->find($evaluateurId);
        if (!$evaluateur) {
            return false; 
        }
        $userService = new UserService();
        $value = $userService->initPassword($evaluateur->user->id);
        
        // $this->pushServiceMessage("info","Traitement title", "message : résultat de traitement");
        return $value;
    }

     
   
}
