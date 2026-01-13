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
     * Cette méthode orchestre la construction initiale du projet :
     * 1. **Configuration des Tâches** : Elle récupère la séquence des tâches à créer basée sur les phases de projet actives (Analyse, Prototype, Réalisation...).
     * 2. **Calcul des Barèmes** : Elle agrège les barèmes des compétences mobilisées pour définir les notes maximales des phases d'évaluation (Prototype N2, Projet N3).
     * 3. **Génération Séquentielle** : Elle itère sur la configuration pour créer les tâches dans l'ordre chronologique défini.
     * 4. **Intégration des Compétences** : Lors de la phase d'Apprentissage, elle déclenche la création des Mobilisations (UA), ce qui génère automatiquement les tâches de type "Tutoriel" via le service dédié.
     *
     * @param mixed $projet Le projet cible à initialiser.
     * @param mixed $session La session de formation contenant le référentiel de compétences (Alignement des UA).
     * @return void
     */
    protected function initializeProjectStructure($projet, $session)
    {
        $priorite = 1;
        $ordre = 1;

        // Récupérer les IDs des phases d'évaluation (N1, N2, N3)
        $phaseN1 = PhaseEvaluation::where('code', 'N1')->value('id');
        $phaseN2 = PhaseEvaluation::where('code', 'N2')->value('id');
        $phaseN3 = PhaseEvaluation::where('code', 'N3')->value('id');

        // Calculer la note pour le prototype et la réalisation
        $notePrototype = $session->alignementUas->sum(function ($alignementUa) {
            return $alignementUa->uniteApprentissage->critereEvaluations
                ->filter(fn($critere) => optional($critere->phaseEvaluation)->code === 'N2')
                ->sum('bareme');
        });

        $noteRealisation = $session->alignementUas->sum(function ($alignementUa) {
            return $alignementUa->uniteApprentissage->critereEvaluations
                ->filter(fn($critere) => optional($critere->phaseEvaluation)->code === 'N3')
                ->sum('bareme');
        });

        // Définition de la structure des tâches via le service
        $tasksConfig = \Modules\PkgCreationProjet\Services\ProjetService::getTasksConfig(
            $session,
            ['N1' => $phaseN1, 'N2' => $phaseN2, 'N3' => $phaseN3],
            ['prototype' => $notePrototype, 'realisation' => $noteRealisation]
        );

        // Itération sur la configuration ordonnée par les phases de projet
        foreach ($tasksConfig as $taskData) {

            // Cas spécial : Tutoriels / Mobilisations
            if (isset($taskData['type']) && $taskData['type'] === 'Tutoriels') {
                // Intégration des mobilisations (et donc des tâches Tuto/Chapitre)
                $this->initMobilisationsUaAndTutoTasks(
                    $projet,
                    $session,
                    $priorite,
                    $ordre,
                    $taskData['phase_projet_id'] ?? null
                );

                // Mise à jour des compteurs basés sur ce qui a été créé par le service MobilisationUa
                $maxOrdre = Tache::where('projet_id', $projet->id)->max('ordre');
                $maxPriorite = Tache::where('projet_id', $projet->id)->max('priorite');

                if ($maxOrdre)
                    $ordre = $maxOrdre + 1;
                if ($maxPriorite)
                    $priorite = $maxPriorite + 1;

                continue;
            }

            // Création des tâches standards
            Tache::firstOrCreate(
                [
                    'projet_id' => $projet->id,
                    'titre' => $taskData['titre'],
                ],
                [
                    'description' => $taskData['description'],
                    'priorite' => $priorite++,
                    'ordre' => $ordre++,
                    'phase_evaluation_id' => $taskData['phase_evaluation_id'],
                    'chapitre_id' => null,
                    'is_live_coding_task' => false,
                    'note' => $taskData['note'] ?? 0,
                    'phase_projet_id' => $taskData['phase_projet_id'] ?? null,
                ]
            );
        }
    }

    /**
     * Initialise les Mobilisations des Unités d'Apprentissage (UA) pour le projet.
     *
     * Cette méthode parcourt les UA définies dans la session de formation et crée les entités 'MobilisationUa' correspondantes.
     * ⚠️ **Effets de bord importants déclenchés par MobilisationUaService::create** :
     * 1. **Création des Tâches Tutoriels** : Via le hook `afterCreateRules`, chaque mobilisation génère automatiquement 
     *    les tâches de type "Tutoriel" (Phase Apprentissage) associées aux chapitres de l'UA.
     * 2. **Synchronisation des Réalisations** : Si des apprenants sont déjà sur le projet, leurs réalisations sont mises à jour
     *    pour inclure les nouvelles compétences à valider (via `syncRealisationsWithNewMobilisationUa`).
     *
     * @param mixed $projet Le projet concerné.
     * @param mixed $session La session de formation source.
     * @param int $priorite (Référence) Compteur de priorité (non utilisé directement ici mais conservé pour signature).
     * @param int $ordre (Référence) Compteur d'ordre (non utilisé directement ici mais conservé pour signature).
     * @param int|null $phaseProjetId ID de la phase de projet "Apprentissage" à transmettre pour la création des tâches.
     * @return void
     */
    protected function initMobilisationsUaAndTutoTasks($projet, $session, &$priorite, &$ordre, $phaseProjetId = null)
    {
        $mobilisationService = new \Modules\PkgCreationProjet\Services\MobilisationUaService();

        foreach ($session->alignementUas as $alignementUa) {
            $data = [
                'projet_id' => $projet->id,
                'unite_apprentissage_id' => $alignementUa->unite_apprentissage_id,
                'description' => $alignementUa->description ?? '',
            ];

            // Utilisation du service pour créer la mobilisation.
            // Le service va automatiquement calculer les critères/barèmes via sa méthode dataCalcul
            // Le service MobilisationUaService se chargera d'ajouter les tâches N1 (Tutoriels)
            $mobilisationService->create($data);
        }


    }
}
