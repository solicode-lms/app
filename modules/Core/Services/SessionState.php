<?php

namespace Modules\Core\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use JsonSerializable;
use Modules\Core\App\Exceptions\BlException;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgFormation\Services\AnneeFormationService;

/**
 * Classe SessionState pour gérer et transmettre les variables de session au JavaScript.
 */
class SessionState implements JsonSerializable
{
    protected $sessionData = [];

    protected bool $loaded = false;

    protected static $cachedUser = null;

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

        // loadUserSessionData un seul fois
        if ($this->loaded){return;} 
        $this->loaded = true;


        $user = Auth::user();
        
        


        
       

        if ($user) {

            // Recharger l'utilisateur avec les relations nécessaires S'IL MANQUE les relations
            $user->loadMissing(['formateur', 'evaluateur', 'apprenant','roles']);

            // Rôle
            $role = $user->roles->first()->name ?? 'Aucun rôle';
            $this->set('user_role', $role);

            // Année formation
            $user_anneeFormation = $this->get("user_annee_formation");
            $annee_formation_id = $this->get("annee_formation_id");

            if ($user_anneeFormation == null || $annee_formation_id == null) {
                $anneeFormation = (new AnneeFormationService())->getCurrentAnneeFormation();
                $this->set('user_annee_formation', $anneeFormation->reference);
                $this->set('annee_formation_id', $anneeFormation->id);
            } else {
                $this->set('user_annee_formation', $user_anneeFormation);
                $this->set('annee_formation_id', $annee_formation_id);
            }

            // ID de l'utilisateur
            $this->set("user_id", $user->id);

            // Formateur
            if ($user->formateur) {
                $this->set("formateur_id", $user->formateur->id);
            }

            // Évaluateur
            if ($user->evaluateur) {
                $this->set("evaluateur_id", $user->evaluateur->id);
            }

            // Apprenant
            if ($user->apprenant) {
                $this->set("apprenant_id", $user->apprenant->id);
            } else {
                if ($role === Role::APPRENANT_ROLE) {
                    Auth::logout();
                    throw new BlException("L'apprenant est en état inactive");
                }
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

    public function isLoaded(): bool
    {
        return $this->loaded;
    }
}
