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
     * Initialise les mobilisations des Unités d'Apprentissage (UA) à partir de la session.
     *
     * Associe les UA de la session au projet. Le calcul des critères est délégué
     * au MobilisationUaService via dataCalcul().
     * @param mixed $projet Le projet concerné.
     * @param mixed $session La session de formation source.
     * @param int $priorite (Référence) Compteur de priorité.
     * @param int $ordre (Référence) Compteur d'ordre.
     * @return void
     */
    protected function initMobilisationsUaAndTutoTasks($projet, $session, &$priorite, &$ordre)
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

    /**
     * Génère l'ensemble de la structure du projet : Tâches et Mobilisations.
     *
     * Crée une séquence de construction standardisée :
     * 1. Tâche d'Analyse
     * 2. Mobilisations des compétences (UA) -> Génère automatiquement les tâches N1 (Tutoriels)
     * 3. Tâche Prototype (N2)
     * 4. Tâche Conception
     * 5. Tâche Réalisation (N3)
     *
     * @param mixed $projet Le projet cible.
     * @param mixed $session La session contenant la structure pédagogique.
     * @return void
     */
    protected function generateTasksAndMobilisations($projet, $session)
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

        // Intégration des mobilisations (et donc des tâches Tuto/Chapitre)
        $this->initMobilisationsUaAndTutoTasks($projet, $session, $priorite, $ordre);

        // Mise à jour des compteurs basés sur ce qui a été créé par le service MobilisationUa
        $maxOrdre = Tache::where('projet_id', $projet->id)->max('ordre');
        $maxPriorite = Tache::where('projet_id', $projet->id)->max('priorite');

        if ($maxOrdre)
            $ordre = $maxOrdre + 1;
        if ($maxPriorite)
            $priorite = $maxPriorite + 1;

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
