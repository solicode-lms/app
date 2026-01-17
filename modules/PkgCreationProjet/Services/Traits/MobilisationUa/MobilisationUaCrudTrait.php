<?php

namespace Modules\PkgCreationProjet\Services\Traits\MobilisationUa;

use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\PkgCreationTache\Services\TacheService;

trait MobilisationUaCrudTrait
{


    /**
     * Règles métier avant création.
     *
     * Règle 1 : Initialisation automatique des critères d'évaluation.
     * Si une UA est liée (`unite_apprentissage_id`) mais que le formulaire n'a pas fourni de critères
     * (cas typique d'une sélection rapide sans éditeur riche ou d'une création par API), le système :
     * 1. Récupère l'UA et ses critères associés via le trait de Calcul.
     * 2. Remplit automatiquement les champs de critères et barèmes pour les phases Prototype (N2) et Projet (N3).
     *
     * @see \Modules\PkgCreationProjet\Services\Traits\MobilisationUa\MobilisationUaDataCalculTrait::enrichDataWithUaCriteriaAndBareme
     */
    public function beforeCreateRules(&$data): array
    {
        if (!empty($data['unite_apprentissage_id']) && empty($data['criteres_evaluation_prototype'])) {
            $this->enrichDataWithUaCriteriaAndBareme($data);
        }
        return $data;
    }

    /**
     * Actions après création.
     *
     * 1. Délégation : Crée automatiquement les tâches de type "Tutoriel" (N1) basées sur les chapitres de l'UA.
     * 2. Maintenance : Met à jour le timestamp du projet parent (`touch`).
     * 3. Synchronisation : Lance le recalcul des réalisations et des tâches du projet pour garantir la cohérence.
     */
    public function afterCreateRules($item): void
    {
        if ($item instanceof \Modules\PkgCreationProjet\Models\MobilisationUa) {
            // 1. Délégation : Création des tâches Tutoriels
            $tacheService = new TacheService();
            $tacheService->createN1TutorielsTasksFromUa($item->projet_id, $item->unite_apprentissage_id);

            // 2. Touch Projet
            if (isset($item->projet)) {
                $item->projet->touch();
            }

            // 3. Sync Tâches Projet
            $this->triggerSyncTacheEtRealisation($item->projet_id);
        }
    }

    public function afterUpdateRules($item): void
    {
        if ($item instanceof \Modules\PkgCreationProjet\Models\MobilisationUa && isset($item->projet)) {
            $item->projet->touch();
            $this->triggerSyncTacheEtRealisation($item->projet_id);
        }
    }

    public function destroy($id)
    {
        $mobilisation = $this->find($id);

        if ($mobilisation) {
            // 1. Supprimer les tâches N1 liées
            $this->deleteAssociatedTasks($mobilisation);
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
     * Supprime les tâches N1 associées à la mobilisation.
     */
    protected function deleteAssociatedTasks($mobilisation)
    {
        $ua = UniteApprentissage::with('chapitres')->find($mobilisation->unite_apprentissage_id);

        if ($ua && $ua->chapitres->isNotEmpty()) {
            $chapitreIds = $ua->chapitres->pluck('id');

            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $mobilisation->projet_id)
                ->whereIn('chapitre_id', $chapitreIds)
                ->delete();
        }
    }

    /**
     * Enrichit les données avec les critères et barèmes extraits de l'UA.
     *
     * Cette méthode récupère l'Unité d'Apprentissage liée, extrait les critères d'évaluation
     * pour les phases Prototype (N2) et Projet (N3), calcule les totaux des barèmes,
     * et formate les critères en listes HTML pour l'affichage dans le formulaire.
     *
     * @param array $data Les données du formulaire passées par référence.
     */
    protected function enrichDataWithUaCriteriaAndBareme(&$data)
    {
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
