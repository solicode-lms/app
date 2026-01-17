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
     * Enrichit les données (calcul auto des critères et barèmes).
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
     * Règles métier avant création : Calcul auto des critères si manquant.
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
     * Actions après création : Génération Tutoriels et Sync Tâches.
     *
     * @param mixed $item
     * @return void
     */
    public function afterCreateRules($item): void
    {
        if ($item instanceof \Modules\PkgCreationProjet\Models\MobilisationUa) {

            // 1. Délégation : Création des tâches Tutoriels via TacheService
            $tacheService = new \Modules\PkgCreationTache\Services\TacheService();
            $tacheService->createTasksFromUa($item->projet_id, $item->unite_apprentissage_id);

            // 2. Touch Projet
            if (isset($item->projet)) {
                $item->projet->touch();
            }

            // 3. Sync Tâches Projet (Modification Réalisation N2/N3)
            $this->triggerSyncTacheEtRealisation($item->projet_id);
        }
    }

    public function afterUpdateRules($item): void
    {
        if ($item instanceof \Modules\PkgCreationProjet\Models\MobilisationUa && isset($item->projet)) {
            $item->projet->touch();

            // 3. Sync Tâches Projet
            $this->triggerSyncTacheEtRealisation($item->projet_id);
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

                // Supprimer les tâches N1 liées
                \Modules\PkgCreationTache\Models\Tache::where('projet_id', $mobilisation->projet_id)
                    ->whereIn('chapitre_id', $chapitreIds)
                    ->delete();
            }
        }

        $result = parent::destroy($id);

        if ($mobilisation) {
            // 2. Touch Projet
            if (isset($mobilisation->projet)) {
                $mobilisation->projet->touch();
            }

            // 3. Sync Tâches Projet
            $this->triggerSyncTacheEtRealisation($mobilisation->projet_id);
        }

        return $result;
    }

    /**
     * Déclenche la synchronisation en cascade des tâches critiques (N2/N3) du projet.
     *
     * @param int $projectId
     * @return void
     */
    protected function triggerSyncTacheEtRealisation($projectId)
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
