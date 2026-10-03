<?php

namespace Modules\PkgCreationProjet\Services\Traits\Projet;

use Modules\PkgSessions\Models\SessionFormation;

/**
 * Trait ProjetCalculTrait
 * 
 * Ce trait regroupe les méthodes de calcul, d'enrichissement de données
 * et de préparation à l'affichage pour l'entité Projet.
 */
trait ProjetCalculTrait
{
    /**
     * Enrichit l'objet projet avec des données calculées ou par défaut.
     *
     * Lors de l'initialisation (création), pré-remplit le titre, la description 
     * et les contraintes à partir de la session de formation sélectionnée.
     *
     * @param mixed $data Les données brutes ou l'objet projet.
     * @return mixed L'objet projet enrichi.
     */
    public function dataCalcul($data)
    {
        $projet = parent::dataCalcul($data);

        // Limiter la liste des sessions selon la filière choisie
        if (!empty($projet->filiere_id)) {
            $viewState = app(\Modules\Core\Services\ViewStateService::class);
            $viewState->set('scope_form.sessionFormation.filiere_id', $projet->filiere_id);
        }

        // En cas de création
        if (empty($projet->id) && $projet->session_formation_id) {
            // Récupérer la session de formation liée
            $session = SessionFormation::find($projet->session_formation_id);

            if ($session) {
                // Hydrater les champs du projet avec les données de la session
                $projet->titre = $session->titre_projet;
                $projet->travail_a_faire = $session->description_projet;
                $projet->critere_de_travail = $session->contraintes_projet;

                // Assigner la filière si présente
                if (!empty($session->filiere_id)) {
                    $projet->filiere_id = $session->filiere_id;
                }
            }
        }

        return $projet;
    }
}
