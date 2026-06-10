<?php

namespace Modules\PkgCreationProjet\Services\Traits\Projet;

use Modules\Core\App\Exceptions\BlException;
use Modules\PkgSessions\Models\SessionFormation;

trait ProjetCrudTrait
{
    /**
     * Applique les règles métier avant la création.
     *
     * @param  array  $data  Les données passées par référence.
     * @return void
     *
     * @throws BlException
     */
    public function beforeCreateRules(array &$data)
    {
        // Si formateur_id n'est pas spécifié, on le lit depuis sessionState (hérité de BaseService)
        if (empty($data['formateur_id'])) {
            $formateurId = $this->sessionState->get('formateur_id');
            if ($formateurId) {
                $data['formateur_id'] = $formateurId;
            }
        }
    }

    /**
     * Exécute les actions nécessaires après la création d'un projet.
     *
     * Cette méthode orchestre l'initialisation du projet :
     * - Importation des compétences (mobilisations UA) depuis la session.
     * - Création automatique de l'arbre des tâches (Analyse, Tutos, Prototype, etc.).
     * - Ajout des livrables par défaut.
     *
     * @param  mixed  $projet  Le projet fraîchement créé.
     * @return void
     */
    public function afterCreateRules($projet)
    {
        if (! $projet || ! $projet->id) {
            return;
        }

        if ($projet->session_formation_id) {
            $session = SessionFormation::with([
                'alignementUas.uniteApprentissage.critereEvaluations.phaseEvaluation',
                'alignementUas.uniteApprentissage.chapitres',
            ])->find($projet->session_formation_id);

            if ($session) {
                // Utilisation des méthodes via ProjetRelationsTrait qui est utilisé dans le service pas le trait
                // Important : Comme ce Trait sera utilisé dans ProjetService, $this aura accès
                // aux méthodes de ProjetRelationsTrait si elles sont aussi utilisées dans le Service.
                $this->initializeProjectStructure($projet, $session);
            }
        }

        // 🔹 Ajout des livrables par défaut via ProjetActionsTrait
        // 🔹 Ajout des livrables par défaut via ProjetActionsTrait
        $this->addDefaultLivrables($projet);

        // 🔹 Règle de Sécurité : Réordonnancement global
        // Garantit que l'ordre des tâches respecte l'ordre des phases (ex: Analyse < Apprentissage)
        $this->reorderTasksByPhase($projet->id);
    }

    /**
     * Réordonne toutes les tâches d'un projet en fonction de l'ordre de leur phase.
     */
    protected function reorderTasksByPhase($projetId)
    {
        $taches = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
            ->join('phase_projets', 'taches.phase_projet_id', '=', 'phase_projets.id')
            ->orderBy('phase_projets.ordre', 'asc')
            ->orderBy('taches.id', 'asc')
            ->select('taches.*')
            ->get();

        $ordre = 1;
        foreach ($taches as $tache) {
            if ($tache->ordre !== $ordre || $tache->priorite !== $ordre) {
                $tache->ordre = $ordre;
                $tache->priorite = $ordre;
                $tache->saveQuietly();
            }
            $ordre++;
        }
    }

    /**
     * Vérifie les règles métier avant la suppression d'un projet.
     *
     * Empêche la suppression si le projet est déjà affecté à des groupes
     * pour garantir l'intégrité des données historiques.
     *
     * @param  mixed  $projet  Le projet à supprimer.
     * @return void
     *
     * @throws BlException Si le projet a des affectations actives.
     */
    public function beforeDeleteRules($projet)
    {
        // Vérification des affectations liées au projet
        $affectations = $projet->affectationProjets()->count();

        if ($affectations > 0) {
            throw new BlException("Impossible de supprimer ce projet : </br> il est encore affecté à un ou plusieurs groupes. </br> Supprimez d'abord les affectations avant de supprimer le projet.");
        }
    }

    /**
     * Vérifie les règles métier avant la mise à jour d'un projet.
     *
     * Interdit la modification de la session de formation une fois
     * que celle-ci a été définie lors de la création.
     *
     * @param  array  $projet  Les données du projet à mettre à jour.
     * @return void
     *
     * @throws BlException Si on tente de changer la session de formation.
     */
    public function beforeUpdateRules($projet)
    {
        // Empêcher la modification de la session de formation
        if (isset($projet['session_formation_id'])) {
            $original = $this->model->find($projet['id'] ?? null);
            if ($original && $original->session_formation_id != $projet['session_formation_id']) {
                throw new BlException('La session de formation ne peut pas être modifiée une fois le projet créé.');
            }
        }
    }
}
