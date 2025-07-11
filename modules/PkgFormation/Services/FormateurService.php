<?php

namespace Modules\PkgFormation\Services;

use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgCompetences\Services\NiveauDifficulteService;
use Modules\PkgFormation\Services\Base\BaseFormateurService;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;

/**
 * Classe FormateurService pour gérer la persistance de l'entité Formateur.
 */
class FormateurService extends BaseFormateurService
{
    protected array $index_with_relations = ['specialites','groupes'];


    /**
     * Crée un formateur et initialise ses dépendances.
     *
     * @param array $data
     * @return mixed
     */
    public function create($data)
    {
        $formateur = parent::create($data);

     
            // Création d'un utilisateur pour le formateur si non existant
            if (is_null($formateur->user_id)) {
            $userService = new UserService();
            $userData = [
                'name' => strtoupper($formateur->nom) . " " . ucfirst($formateur->prenom),
                'email' => $formateur->email 
                    ? $formateur->email 
                    : strtolower(trim(str_replace(' ', '-', $formateur->nom))) . strtolower(trim(str_replace(' ', '-', $formateur->prenom))) . "@ofppt-edu.ma",
                'password' => bcrypt("12345678"), // Hash du mot de passe pour sécurité
            ];

            $user = $userService->create($userData);

            if ($user) {
                $user->assignRole(Role::FORMATEUR_ROLE);
                $formateur->user_id = $user->id;
                $formateur->save();
            }
        }


        // TODO : Il faut créer des état de réalisation des tâches
        // Création des états de réalisation de projets pour le formateur
        // $etatsRealisationProjetService = new EtatsRealisationProjetService();
        // $etatsRealisationProjet = [
        //     ["En cours", "Le projet est en cours de réalisation."],
        //     ["Terminé", "Le projet a été finalisé avec succès."],
        //     ["Annulé", "Le projet a été abandonné ou annulé."]
        // ];

        // foreach ($etatsRealisationProjet as $etat) {
        //     $etatsRealisationProjetService->updateOrCreate ([
        //         "titre" => $etat[0],
        //         "formateur_id" => $formateur->id
        //     ],[
        //         "titre" => $etat[0],
        //         "description" => $etat[1],
        //         "formateur_id" => $formateur->id
        //     ]);
        // }

        return $formateur;
    }


    public function initPassword(int $formateurId)
    {
        $formateur = $this->find($formateurId);
        if (!$formateur) {
            return false; 
        }
        $userService = new UserService();
        $value = $userService->initPassword($formateur->user->id);
        return $value;
    }


    /**
     * Trouver la liste des apprenants enseignés par un formateur.
     *
     * @param int $formateur_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getApprenants($formateur_id)
    {
        return Apprenant::whereHas('groupes', function ($query) use ($formateur_id) {
            $query->whereHas('formateurs', function ($q) use ($formateur_id) {
                $q->where('formateurs.id', $formateur_id);
            });
        })->get();
    }
}
