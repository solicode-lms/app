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
     * Règles métier à appliquer avant la création d'une mobilisation.
     * 
     * Calcule automatiquement les critères et barèmes (Prototype & Projet)
     * si une UA est fournie mais que les champs sont vides.
     */
    public function beforeCreateRules(&$data): array
    {
        // Appel parent si nécessaire (mais BaseService n'a pas forcément de beforeCreateRules qui retourne un array)
        // Note: Dans votre architecture, les Rules prennent souvent l'objet ou le tableau par référence ou retournent void/array.
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

    public function afterCreateRules($item): void
    {
        if ($item instanceof \Modules\PkgCreationProjet\Models\MobilisationUa) {
            $realisationProjetService = new \Modules\PkgRealisationProjets\Services\RealisationProjetService();
            $realisationProjetService->addMobilisationToProjectRealisations($item->projet_id, $item);

            // Mise à jour de la date de modification du projet parent
            if (isset($item->projet)) {
                $item->projet->touch();
            }
        }
    }

    public function afterUpdateRules($item): void
    {
        if ($item instanceof \Modules\PkgCreationProjet\Models\MobilisationUa && isset($item->projet)) {
            $item->projet->touch();
        }
    }

    public function destroy($id)
    {
        $mobilisation = $this->find($id);

        $result = parent::destroy($id);

        if ($mobilisation) {
            $realisationProjetService = new \Modules\PkgRealisationProjets\Services\RealisationProjetService();
            $realisationProjetService->removeMobilisationFromProjectRealisations(
                $mobilisation->projet_id,
                $mobilisation->unite_apprentissage_id
            );

            // Mise à jour de la date de modification du projet parent
            if (isset($mobilisation->projet)) {
                $mobilisation->projet->touch();
            }
        }

        return $result;
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
