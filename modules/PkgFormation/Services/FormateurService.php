<?php

namespace Modules\PkgFormation\Services;

use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgCompetences\Services\NiveauDifficulteService;
use Modules\PkgFormation\Services\Base\BaseFormateurService;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;
use Modules\PkgCompetences\Models\NiveauDifficulte;

/**
 * Classe FormateurService pour gérer la persistance de l'entité Formateur.
 */
class FormateurService extends BaseFormateurService
{
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


        // Création des niveaux de difficulté pour le formateur
        $niveauDifficulteService = new NiveauDifficulteService();
        $niveauxDifficulte = [
            ["Débutant", "Notions de base acquises.", 0, 5],
            ["Intermédiaire", "Compétences appliquées avec assistance limitée.", 6, 10],
            ["Avancé", "Bonne autonomie dans l'application des compétences.", 11, 15],
            ["Expert", "Expertise démontrée avec capacité à résoudre des problèmes complexes.", 16, 18],
            ["Maîtrise complète", "Maîtrise totale et capacité à enseigner ou guider les autres.", 19, 20]
        ];

        foreach ($niveauxDifficulte as $niveau) {
            $niveauDifficulteService->updateOrCreate(
                [  "nom" => $niveau[0], "formateur_id" => $formateur->id],
                [
                "nom" => $niveau[0],
                "description" => $niveau[1],
                "noteMin" => $niveau[2],
                "noteMax" => $niveau[3],
                "formateur_id" => $formateur->id
            ]);
        }

        // Création des états de réalisation de projets pour le formateur
        $etatsRealisationProjetService = new EtatsRealisationProjetService();
        $etatsRealisationProjet = [
            ["En cours", "Le projet est en cours de réalisation."],
            ["Terminé", "Le projet a été finalisé avec succès."],
            ["Annulé", "Le projet a été abandonné ou annulé."]
        ];

        foreach ($etatsRealisationProjet as $etat) {
            $etatsRealisationProjetService->updateOrCreate ([
                "titre" => $etat[0],
                "formateur_id" => $formateur->id
            ],[
                "titre" => $etat[0],
                "description" => $etat[1],
                "formateur_id" => $formateur->id
            ]);
        }

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
}
