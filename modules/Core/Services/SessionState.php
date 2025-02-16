<?php

namespace Modules\Core\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use JsonSerializable;
use Modules\PkgFormation\Services\AnneeFormationService;

/**
 * Classe SessionState pour gérer et transmettre les variables de session au JavaScript.
 */
class SessionState implements JsonSerializable
{
    protected $sessionData = [];

    /**
     * Ajouter une variable à la session.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value)
    {
        $this->sessionData[$key] = $value;
        Session::put($key, $value);
    }

    /**
     * Récupérer une variable de session.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Session::get($key, $default);
    }

    /**
     * Obtenir toutes les variables de session.
     *
     * @return array
     */
    public function all(): array
    {
        // return Session::all();
        return $this->sessionData;
    }

    /**
     * Charger les variables de session basées sur l'utilisateur authentifié.
     */
    public function loadUserSessionData()
    {
        $user = Auth::user();
    
        if ($user) {
            // Stocker le rôle de l'utilisateur
            $role = $user->roles->first()->name ?? 'Aucun rôle';
            $this->set('user_role', $role);
    
            // Récupérer l'année de formation si l'utilisateur est un apprenant
            $user_anneeFormation = $this->get("user_annee_formation");
            $annee_formation_id = $this->get("annee_formation_id");

            // Si l'année de formation n'existe pas, récupérer l'année en cours
            if ($user_anneeFormation == null || $annee_formation_id == null) {
                $anneeFormation = (new AnneeFormationService())->getCurrentAnneeFormation();
                 // Stocker l'année de formation
                $this->set('user_annee_formation', $anneeFormation->reference);
                $this->set('annee_formation_id', $anneeFormation->id);
            }else{
                $this->set('user_annee_formation', $user_anneeFormation);
                $this->set('annee_formation_id', $annee_formation_id);
                
            }  

            // ajouter l'id de user dans la session
            $this->set("user_id",$user->id);
            $formateur = $user->formateur;
            if ($formateur) {
                $this->set("formateur_id",$formateur->id);
            }
           
            $apprenant = $user->apprenant;
            if ($apprenant) {
                $this->set("apprenant_id",$apprenant->id);
            }
        }
    }


    /**
     * Convertir en JSON pour transmission au JavaScript.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'session_data' => $this->all(),
        ];
    }
}
