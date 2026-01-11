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
     * Met à jour ou initialise les mobilisations des Unités d'Apprentissage (UA).
     *
     * Associe les UA de la session au projet. Le calcul des critères est délégué
     * au MobilisationUaService via dataCalcul().
     *
     * @param mixed $projet Le projet concerné.
     * @param mixed $session La session de formation source.
     * @return void
     */
    protected function updateMobilisationsUa($projet, $session)
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
            $mobilisationService->create($data);
        }
    }

    /**
     * Génère et ajoute les tâches du projet basées sur le scénario pédagogique.
     *
     * Crée une séquence de tâches standardisée :
     * 1. Analyse
     * 2. Tutoriels (basés sur les chapitres de la session) - Niveau N1
     * 3. Prototype - Niveau N2
     * 4. Conception
     * 5. Réalisation - Niveau N3
     *
     * @param mixed $projet Le projet cible.
     * @param mixed $session La session contenant la structure pédagogique.
     * @return void
     */
    protected function addProjectTasks($projet, $session)
    {
        $priorite = 1; // compteur de priorité progressive
        $ordre = 1;   // compteur d'ordre

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

        // Tâche Analyse
        Tache::firstOrCreate(
            [
                'projet_id' => $projet->id,
                'titre' => 'Analyse',
            ],
            [
                'description' => 'Analyse du projet',
                'priorite' => $priorite++,
                'ordre' => $ordre++,
                'phase_evaluation_id' => null,
                'chapitre_id' => null
            ]
        );

        // Tâches Chapitre
        foreach ($session->alignementUas as $alignementUa) {
            foreach ($alignementUa->uniteApprentissage->chapitres as $chapitre) {
                Tache::firstOrCreate(
                    [
                        'projet_id' => $projet->id,
                        'titre' => 'Tutoriel : ' . $chapitre->nom,
                    ],
                    [
                        'description' => $chapitre->description ?? '',
                        'priorite' => $priorite++,
                        'ordre' => $ordre++,
                        'phase_evaluation_id' => $phaseN1,
                        'chapitre_id' => $chapitre->id
                    ]
                );
            }
        }

        // Tâche Prototype
        Tache::firstOrCreate(
            [
                'projet_id' => $projet->id,
                'titre' => $session->titre_prototype ? "Prototype : " . $session->titre_prototype : 'Prototype',
            ],
            [
                'description' => trim(($session->description_prototype ?? '') . "</br><b>Contraintes</b>" . ($session->contraintes_prototype ?? '')),
                'priorite' => $priorite++,
                'ordre' => $ordre++,
                'phase_evaluation_id' => $phaseN2,
                'chapitre_id' => null,
                'is_live_coding_task' => false,
                'note' => $notePrototype
            ]
        );

        // Tâche Conception
        Tache::firstOrCreate(
            [
                'projet_id' => $projet->id,
                'titre' => 'Conception',
            ],
            [
                'description' => 'Conception du projet',
                'priorite' => $priorite++,
                'ordre' => $ordre++,
                'phase_evaluation_id' => null,
                'chapitre_id' => null
            ]
        );

        // Tâche Réalisation
        Tache::firstOrCreate(
            [
                'projet_id' => $projet->id,
                'titre' => 'Réalisation',
            ],
            [
                'description' => trim(($session->description_projet ?? '') . "</br><b>Contraintes</b>" . ($session->contraintes_projet ?? '')),
                'priorite' => $priorite++,
                'ordre' => $ordre++,
                'phase_evaluation_id' => $phaseN3,
                'chapitre_id' => null,
                'is_live_coding_task' => false,
                'note' => $noteRealisation
            ]
        );
    }
}
