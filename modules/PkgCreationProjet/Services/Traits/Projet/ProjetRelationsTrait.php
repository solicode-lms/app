<?php

namespace Modules\PkgCreationProjet\Services\Traits\Projet;

use Illuminate\Support\Facades\DB;
use Modules\PkgCompetences\Models\PhaseEvaluation;
use Modules\PkgCreationTache\Models\Tache;

/**
 * Trait ProjetRelationsTrait
 * 
 * Ce trait gère la création des sous-entités et relations complexes du Projet.
 */
trait ProjetRelationsTrait
{

    /**
     * Initialise la structure complète du projet en fonction des phases définies et du contenu pédagogique.
     *
     * Cette méthode orchestre la construction initiale du projet en deux étapes distinctes :
     * 1. **Création des Tâches Standards** : Itère sur la configuration pour créer les tâches structurelles (Analyse, Prototype, Réalisation...) en ignorant temporairement les tutoriels.
     * 2. **Intégration des Compétences (Tutoriels)** : Une fois la structure de base en place, elle déclenche la création des Mobilisations (UA) et de leurs tâches associées.
     *
     * @param mixed $projet Le projet cible à initialiser.
     * @param mixed $session La session de formation contenant le référentiel de compétences (Alignement des UA).
     * @return void
     */
    protected function initializeProjectStructure($projet, $session)
    {
        $tacheService = new \Modules\PkgCreationTache\Services\TacheService();
        $priorite = 1;
        $ordre = 1;
        $phaseProjetApprentissageId = null;

        // Définition de la structure des tâches via le service
        $tasksConfig = \Modules\PkgCreationProjet\Services\ProjetService::getTasksConfig($session);

        // 1. Création des Tâches Structurelles Standards
        foreach ($tasksConfig as $taskData) {

            // On ignore le marqueur 'Tutoriels', mais on capture l'ID de sa phase pour l'utiliser après
            if (isset($taskData['type']) && $taskData['type'] === 'Tutoriels') {
                $phaseProjetApprentissageId = $taskData['phase_projet_id'] ?? null;
                continue;
            }

            // Création des tâches standards
            $exists = Tache::where('projet_id', $projet->id)
                ->where('titre', $taskData['titre'])
                ->exists();

            if (!$exists) {
                $tacheService->create([
                    'projet_id' => $projet->id,
                    'titre' => $taskData['titre'],
                    'description' => $taskData['description'],
                    'priorite' => $priorite,
                    'ordre' => $ordre,
                    'phase_evaluation_id' => $taskData['phase_evaluation_id'],
                    'chapitre_id' => null,
                    'is_live_coding_task' => false,
                    'phase_projet_id' => $taskData['phase_projet_id'] ?? null,
                ]);
            }

            // Incrémentation pour la prochaine itération
            $priorite++;
            $ordre++;
        }

        // 2. Intégration des Mobilisations et Tâches Tutoriels (N1)
        // Note : L'ordre final sera recalculé par reorderTasksByPhase() après l'insertion.
        if ($phaseProjetApprentissageId) {
            $this->createMobilisationFromSession($projet, $session);
        }
    }

    /**
     * Crée les mobilisations UA depuis la session.
     *
     * Cette action déclenchera en cascade la création des tâches "Tutoriels" via le MobilisationUaService.
     *
     * @param mixed $projet
     * @param mixed $session
     * @return void
     */
    protected function createMobilisationFromSession($projet, $session)
    {
        $mobilisationService = new \Modules\PkgCreationProjet\Services\MobilisationUaService();

        foreach ($session->alignementUas as $alignementUa) {
            $data = [
                'projet_id' => $projet->id,
                'unite_apprentissage_id' => $alignementUa->unite_apprentissage_id,
                'description' => $alignementUa->description ?? '',
            ];

            // Déclenche le hook afterCreateRules : Création automatique des tâches N1 (Tutoriels)
            $mobilisationService->create($data);
        }


    }
}
