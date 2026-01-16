<?php
namespace Modules\PkgCreationProjet\Services;

use Modules\PkgCreationProjet\Services\Base\BaseMobilisationUaService;
use Modules\PkgCompetences\Models\UniteApprentissage;

/**
 * Classe MobilisationUaService pour gérer la persistance de l'entité MobilisationUa.
 */
class MobilisationUaService extends BaseMobilisationUaService
{
    /**
     * Enrichit les données de la mobilisation pour le formulaire.
     * 
     * Calcule automatiquement les critères et barèmes (Prototype & Projet)
     * si une UA est fournie.
     */
    public function dataCalcul($data)
    {
        $data = parent::dataCalcul($data);

        // Si on a une UA mais pas encore les critères calculés (ou si on veut les forcer pour l'affichage)
        if (!empty($data['unite_apprentissage_id'])) {

            $ua = UniteApprentissage::with('critereEvaluations.phaseEvaluation')->find($data['unite_apprentissage_id']);

            if ($ua) {
                [$criteresPrototype, $baremePrototype] = $this->extractCriteresEtBareme($ua, 'N2');
                [$criteresProjet, $baremeProjet] = $this->extractCriteresEtBareme($ua, 'N3');

                $data['criteres_evaluation_prototype'] = $this->formatCriteres($criteresPrototype);
                $data['criteres_evaluation_projet'] = $this->formatCriteres($criteresProjet);
                $data['bareme_evaluation_prototype'] = $baremePrototype;
                $data['bareme_evaluation_projet'] = $baremeProjet;
            }
        }

        return $data;
    }

    /**
     * Règles métier à appliquer avant la création d'une mobilisation.
     * 
     * Calcule automatiquement les critères et barèmes (Prototype & Projet)
     * si une UA est fournie mais que les champs sont vides.
     */
    public function beforeCreateRules(&$data): array
    {
        // Appel parent si nécessaire (mais BaseService n'a pas forcément de beforeCreateRules qui retourne un array)
        // Je suppose ici que je peux modifier $data et le retourner.

        // Si on a une UA mais pas encore les critères calculés
        if (!empty($data['unite_apprentissage_id']) && empty($data['criteres_evaluation_prototype'])) {

            $ua = UniteApprentissage::with('critereEvaluations.phaseEvaluation')->find($data['unite_apprentissage_id']);

            if ($ua) {
                [$criteresPrototype, $baremePrototype] = $this->extractCriteresEtBareme($ua, 'N2');
                [$criteresProjet, $baremeProjet] = $this->extractCriteresEtBareme($ua, 'N3');

                $data['criteres_evaluation_prototype'] = $this->formatCriteres($criteresPrototype);
                $data['criteres_evaluation_projet'] = $this->formatCriteres($criteresProjet);
                $data['bareme_evaluation_prototype'] = $baremePrototype;
                $data['bareme_evaluation_projet'] = $baremeProjet;
            }
        }

        return $data;
    }

    /**
     * Actions effectuées après la création d'une Mobilisation UA.
     *
     * Cette méthode orchestre les conséquences de l'ajout d'une compétence (UA) au projet :
     * 1. **Génération des Tutoriels** : Pour chaque chapitre de l'UA, une tâche de type "Tutoriel" est créée.
     *    Ces tâches sont assignées à la phase "Apprentissage" (APPRENTISSAGE) et au niveau d'évaluation N1.
     * 2. **Calcul de l'Ordre** : L'ordre des nouvelles tâches est déterminé dynamiquement :
     *    - Si des tâches existent déjà dans la phase Apprentissage, on les suit.
     *    - Sinon, on s'insère à la suite des tâches des phases précédentes (ex: après l'Analyse), 
     *      garantissant une continuité logique dans le workflow du projet.
     * 3. **Synchronisation des Apprenants** : Si des élèves travaillent déjà sur le projet, leurs réalisations
     *    sont mises à jour pour inclure ces nouvelles tâches et compétences à valider.
     *
     * @param mixed $item La mobilisation créée (instance de MobilisationUa).
     * @return void
     */
    public function afterCreateRules($item): void
    {
        if ($item instanceof \Modules\PkgCreationProjet\Models\MobilisationUa) {

            // 1. Délégation SRP : Demander à TacheService de créer les tâches liées aux chapitres
            $tacheService = new \Modules\PkgCreationTache\Services\TacheService();
            $tacheService->createTasksFromUa($item->projet_id, $item->unite_apprentissage_id);

            // 2. Mise à jour de la date de modification du projet parent
            if (isset($item->projet)) {
                $item->projet->touch();
            }

            // 3. Déclencher la synchronisation des tâches du projet
            $this->triggerTaskSynchronization($item->projet_id);
        }
    }

    public function afterUpdateRules($item): void
    {
        if ($item instanceof \Modules\PkgCreationProjet\Models\MobilisationUa && isset($item->projet)) {
            $item->projet->touch();

            // 3. Déclencher la synchronisation des tâches du projet
            $this->triggerTaskSynchronization($item->projet_id);
        }
    }

    public function destroy($id)
    {
        $mobilisation = $this->find($id);

        if ($mobilisation) {
            // 1. Supprimer les tâches associées (TOUTES les tâches liées aux chapitres de l'UA)
            $ua = \Modules\PkgCompetences\Models\UniteApprentissage::with('chapitres')->find($mobilisation->unite_apprentissage_id);

            if ($ua && $ua->chapitres->isNotEmpty()) {
                $chapitreIds = $ua->chapitres->pluck('id');

                // Supprimer TOUTES les tâches liées à ces chapitres pour ce projet
                \Modules\PkgCreationTache\Models\Tache::where('projet_id', $mobilisation->projet_id)
                    ->whereIn('chapitre_id', $chapitreIds)
                    ->delete();
            }
        }

        $result = parent::destroy($id);

        if ($mobilisation) {
            // Mise à jour de la date de modification du projet parent
            if (isset($mobilisation->projet)) {
                $mobilisation->projet->touch();
            }

            // 3. Déclencher la synchronisation des tâches du projet
            $this->triggerTaskSynchronization($mobilisation->projet_id);
        }

        return $result;
    }

    /**
     * Déclenche la synchronisation des tâches du projet.
     * 
     * Cette méthode identifie les tâches critiques du projet (Phases N2 - Prototype et N3 - Réalisation)
     * et initie une mise à jour sur chacune d'elles.
     * 
     * Cette action en cascade permet de :
     * 1. Recalculer la note maximale de la tâche via `TacheService::beforeUpdateRules`
     *    (basée sur la somme des barèmes des UA mobilisées).
     * 2. Mettre à jour la phase du projet si nécessaire.
     * 3. Synchroniser les réalisations de compétences des apprenants via `TacheService::afterUpdateRules`
     *    (création des entrées RealisationUaPrototype/Projet).
     * 
     * Cette centralisation garantit la cohérence des données d'évaluation suite à toute modification
     * des mobilisations de compétences.
     *
     * @param int $projectId L'identifiant du projet.
     * @return void
     */
    protected function triggerTaskSynchronization($projectId)
    {
        if (!$projectId)
            return;

        // Trouver les IDs des phases d'évaluation N2 et N3
        $phases = \Modules\PkgCompetences\Models\PhaseEvaluation::whereIn('code', ['N2', 'N3'])->pluck('id');

        if ($phases->isEmpty())
            return;

        // Trouver les tâches correspondantes pour ce projet
        $taches = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projectId)
            ->whereIn('phase_evaluation_id', $phases)
            ->get();

        if ($taches->isEmpty())
            return;

        $tacheService = new \Modules\PkgCreationTache\Services\TacheService();

        foreach ($taches as $tache) {
            // On appelle update avec juste l'ID pour déclencher la logique beforeUpdateRules
            // qui se chargera de recalculer la note basée sur les UA actuelles.
            $tacheService->update($tache->id, ['id' => $tache->id]);
        }
    }

    /**
     * Extrait les critères et le barème depuis une UA pour un niveau donné.
     */
    protected function extractCriteresEtBareme($ua, $niveau)
    {
        $criteres = $ua->critereEvaluations
            ->filter(fn($critere) => optional($critere->phaseEvaluation)->code === $niveau)
            ->pluck('intitule')
            ->toArray();

        $bareme = $ua->critereEvaluations
            ->filter(fn($critere) => optional($critere->phaseEvaluation)->code === $niveau)
            ->sum('bareme');

        return [$criteres, $bareme];
    }

    /**
     * Formate une liste de critères en HTML.
     */
    protected function formatCriteres(array $criteres): string
    {
        if (empty($criteres))
            return '';
        return '<ul><li>' . implode('</li><li>', $criteres) . '</li></ul>';
    }
}
